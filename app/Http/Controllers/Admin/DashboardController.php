<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function index(): View
    {
        $today = Carbon::today();
        $yesterday = $today->copy()->subDay();

        $paidOrders = fn ($day) => Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()]);

        $revenueToday = (float) $paidOrders($today)->sum('total');
        $revenueYesterday = (float) $paidOrders($yesterday)->sum('total');
        $revenueDelta = $this->percentChange($revenueToday, $revenueYesterday);

        $todaysOrders = $paidOrders($today)->count();
        $ordersYesterday = $paidOrders($yesterday)->count();
        $ordersDelta = $this->percentChange((float) $todaysOrders, (float) $ordersYesterday);

        $totalTables = DiningTable::query()->count();
        $activeTables = DiningTable::query()->where('status', 'occupied')->count();
        $availableTables = DiningTable::query()->where('status', 'available')->count();
        $tableCapacity = $totalTables > 0 ? (int) round($activeTables / $totalTables * 100) : 0;

        $weeklyRevenue = collect(range(6, 0))->map(function ($daysAgo) {
            $day = Carbon::today()->subDays($daysAgo);
            $value = (float) Order::query()
                ->where('payment_status', 'paid')
                ->whereBetween('paid_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
                ->sum('total');

            return ['label' => $day->format('D'), 'value' => $value];
        })->values();

        $kitchenTickets = Order::query()
            ->with(['table', 'items'])
            ->whereIn('kitchen_status', ['waiting', 'cooking', 'ready'])
            ->oldest('created_at')
            ->limit(3)
            ->get()
            ->map(function (Order $order) {
                $minutes = (int) $order->created_at->diffInMinutes(now());

                return [
                    'id' => '#' . ($order->order_number ? \Illuminate\Support\Str::after($order->order_number, 'ORD-') : $order->id),
                    'place' => $order->table ? 'Table ' . $order->table->formatted_number : 'Takeout #' . $order->id,
                    'time' => $minutes . 'm',
                    'urgency' => $minutes >= 20 ? 'high' : ($minutes >= 10 ? 'mid' : 'low'),
                    'items' => $order->items->map(fn ($item) => [
                        'qty' => $item->quantity,
                        'name' => $item->name,
                        'note' => $item->note,
                    ])->all(),
                ];
            });

        $activeKitchenCount = Order::query()->whereIn('kitchen_status', ['waiting', 'cooking', 'ready'])->count();

        $popularToday = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->leftJoin('categories', 'categories.id', '=', 'menu_items.category_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.paid_at', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->groupBy('order_items.menu_item_id', 'order_items.name', 'categories.name', 'menu_items.image')
            ->select(
                'order_items.name',
                'categories.name as category',
                'menu_items.image',
                DB::raw('SUM(order_items.quantity) as units_sold')
            )
            ->orderByDesc('units_sold')
            ->limit(4)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'category' => $row->category ?? '—',
                'orders' => (int) $row->units_sold,
                'image' => $row->image ? asset('storage/' . $row->image) : null,
            ]);

        $recentOrders = Order::query()
            ->with('table')
            ->where('payment_status', 'paid')
            ->latest('paid_at')
            ->limit(5)
            ->get()
            ->map(fn (Order $order) => [
                'id' => '#' . ($order->order_number ? \Illuminate\Support\Str::after($order->order_number, 'ORD-') : $order->id),
                'table' => $order->table ? 'Table ' . $order->table->formatted_number : 'Takeout #' . $order->id,
                'total' => (float) $order->total,
                'status' => 'Paid',
            ]);

        return view('Admin.dashboard', [
            'revenueToday' => $revenueToday,
            'revenueDelta' => $revenueDelta,
            'todaysOrders' => $todaysOrders,
            'ordersDelta' => $ordersDelta,
            'activeTables' => $activeTables,
            'tableCapacity' => $tableCapacity,
            'availableTables' => $availableTables,
            'totalTables' => $totalTables,
            'weeklyRevenue' => $weeklyRevenue,
            'kitchenTickets' => $kitchenTickets,
            'activeKitchenCount' => $activeKitchenCount,
            'popularToday' => $popularToday,
            'recentOrders' => $recentOrders,
        ]);
    }

    private function percentChange(float $current, float $previous): float
    {
        if ($previous == 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / abs($previous)) * 100, 1);
    }
}