<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller implements HasMiddleware
{
    // Palet warna donut, dipakai berurutan sesuai ranking revenue kategori.
    private const CHART_COLORS = ['#9a3412', '#0f766e', '#2dd4bf', '#e7d4c4', '#f59e0b', '#64748b'];

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function index(Request $request): View
    {
        [$start, $end] = $this->resolveDateRange($request);
        $granularity = $request->query('granularity', 'daily') === 'weekly' ? 'weekly' : 'daily';

        $days = $start->diffInDays($end) + 1;
        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($days - 1)->startOfDay();

        $paidOrders = fn ($from, $to) => Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [$from, $to]);

        $revenue = (float) $paidOrders($start, $end)->sum('total');
        $prevRevenue = (float) $paidOrders($prevStart, $prevEnd)->sum('total');

        $transactions = $paidOrders($start, $end)->count();
        $prevTransactions = $paidOrders($prevStart, $prevEnd)->count();

        $avgOrderValue = $transactions > 0 ? $revenue / $transactions : 0;
        $prevAvgOrderValue = $prevTransactions > 0 ? $prevRevenue / $prevTransactions : 0;

        // Sales by category (revenue per kategori dalam rentang tanggal, cuma dari order yg sudah dibayar).
        $categorySales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->join('categories', 'categories.id', '=', 'menu_items.category_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.paid_at', [$start, $end])
            ->groupBy('categories.id', 'categories.name')
            ->select('categories.id', 'categories.name', DB::raw('SUM(order_items.unit_price * order_items.quantity) as revenue'))
            ->orderByDesc('revenue')
            ->get();

        $totalCategoryRevenue = (float) $categorySales->sum('revenue');
        $bestCategory = $categorySales->first();

        $categoryChart = $categorySales->take(5)->values()->map(function ($row, $i) use ($totalCategoryRevenue) {
            return [
                'name' => $row->name,
                'revenue' => (float) $row->revenue,
                'percent' => $totalCategoryRevenue > 0 ? round($row->revenue / $totalCategoryRevenue * 100) : 0,
                'color' => self::CHART_COLORS[$i] ?? '#94a3b8',
            ];
        });

        // Revenue over time (chart bar).
        $revenueSeries = $this->buildRevenueSeries($start, $end, $granularity);

        // Best selling menu items (top 5) + trend vs periode sebelumnya.
        $bestSellers = $this->bestSellingItems($start, $end, $prevStart, $prevEnd, 5);

        return view('Landing.reports', [
            'start' => $start,
            'end' => $end,
            'granularity' => $granularity,
            'revenue' => $revenue,
            'revenueChange' => $this->percentChange($revenue, $prevRevenue),
            'transactions' => $transactions,
            'transactionsChange' => $this->percentChange($transactions, $prevTransactions),
            'avgOrderValue' => $avgOrderValue,
            'avgOrderValueChange' => $this->percentChange($avgOrderValue, $prevAvgOrderValue),
            'bestCategory' => $bestCategory,
            'bestCategoryPercent' => $bestCategory && $totalCategoryRevenue > 0
                ? round($bestCategory->revenue / $totalCategoryRevenue * 100)
                : 0,
            'categoryChart' => $categoryChart,
            'revenueSeries' => $revenueSeries,
            'bestSellers' => $bestSellers,
        ]);
    }

    public function items(Request $request): View
    {
        [$start, $end] = $this->resolveDateRange($request);
        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($start->diffInDays($end))->startOfDay();

        $bestSellers = $this->bestSellingItems($start, $end, $prevStart, $prevEnd, 50);

        return view('Landing.report-items', [
            'start' => $start,
            'end' => $end,
            'bestSellers' => $bestSellers,
        ]);
    }

    public function export(Request $request): Response
    {
        [$start, $end] = $this->resolveDateRange($request);
        $prevEnd = $start->copy()->subDay()->endOfDay();
        $prevStart = $prevEnd->copy()->subDays($start->diffInDays($end))->startOfDay();

        $rows = $this->bestSellingItems($start, $end, $prevStart, $prevEnd, 1000);

        $csv = "Item Name,Category,Units Sold,Revenue\n";
        foreach ($rows as $row) {
            $csv .= sprintf('"%s","%s",%d,%.2f' . "\n", $row['name'], $row['category'], $row['units_sold'], $row['revenue']);
        }

        $filename = 'savora-report-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function resolveDateRange(Request $request): array
    {
        $start = $request->query('start')
            ? Carbon::parse($request->query('start'))->startOfDay()
            : now()->startOfMonth();

        $end = $request->query('end')
            ? Carbon::parse($request->query('end'))->endOfDay()
            : now()->endOfDay();

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    private function percentChange(float $current, float $previous): array
    {
        if ($previous == 0.0) {
            $percent = $current > 0 ? 100.0 : 0.0;
        } else {
            $percent = round((($current - $previous) / abs($previous)) * 100, 1);
        }

        return [
            'percent' => abs($percent),
            'direction' => $percent > 0 ? 'up' : ($percent < 0 ? 'down' : 'flat'),
        ];
    }

    private function buildRevenueSeries(Carbon $start, Carbon $end, string $granularity): array
    {
        $series = [];

        if ($granularity === 'weekly') {
            $cursor = $start->copy()->startOfWeek();
            $weekIndex = 1;

            while ($cursor->lessThanOrEqualTo($end)) {
                $weekEnd = $cursor->copy()->endOfWeek()->min($end);

                $value = (float) Order::query()
                    ->where('payment_status', 'paid')
                    ->whereBetween('paid_at', [$cursor->copy()->max($start), $weekEnd])
                    ->sum('total');

                $series[] = ['label' => 'Week ' . $weekIndex, 'value' => $value];
                $cursor->addWeek();
                $weekIndex++;
            }
        } else {
            $period = CarbonPeriod::create($start->copy()->startOfDay(), $end->copy()->startOfDay());
            $useWeekdayLabel = $start->diffInDays($end) <= 7;

            foreach ($period as $date) {
                $value = (float) Order::query()
                    ->where('payment_status', 'paid')
                    ->whereBetween('paid_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                    ->sum('total');

                $series[] = [
                    'label' => $useWeekdayLabel ? $date->format('D') : $date->format('M j'),
                    'value' => $value,
                ];
            }
        }

        return $series;
    }

    private function bestSellingItems(Carbon $start, Carbon $end, Carbon $prevStart, Carbon $prevEnd, int $limit): \Illuminate\Support\Collection
    {
        $current = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id')
            ->leftJoin('categories', 'categories.id', '=', 'menu_items.category_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.paid_at', [$start, $end])
            ->groupBy('order_items.menu_item_id', 'order_items.name', 'categories.name')
            ->select(
                'order_items.menu_item_id',
                'order_items.name',
                'categories.name as category',
                DB::raw('SUM(order_items.quantity) as units_sold'),
                DB::raw('SUM(order_items.unit_price * order_items.quantity) as revenue')
            )
            ->orderByDesc('units_sold')
            ->limit($limit)
            ->get();

        $prevUnitsByItem = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.paid_at', [$prevStart, $prevEnd])
            ->groupBy('order_items.menu_item_id')
            ->select('order_items.menu_item_id', DB::raw('SUM(order_items.quantity) as units_sold'))
            ->pluck('units_sold', 'menu_item_id');

        return $current->map(function ($row) use ($prevUnitsByItem) {
            $prevUnits = (int) ($prevUnitsByItem[$row->menu_item_id] ?? 0);
            $trend = $this->percentChange((float) $row->units_sold, (float) $prevUnits);

            return [
                'name' => $row->name,
                'category' => $row->category ?? '—',
                'units_sold' => (int) $row->units_sold,
                'revenue' => (float) $row->revenue,
                'trend_percent' => round($trend['percent']),
                'trend_direction' => $trend['direction'],
            ];
        });
    }
}