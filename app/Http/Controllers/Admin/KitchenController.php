<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class KitchenController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner,chef'),
        ];
    }

    public function index(Request $request): View
    {
        $station = $request->query('station', 'all');

        $orders = Order::query()
            ->with(['table', 'user', 'items'])
            ->whereIn('kitchen_status', ['waiting', 'cooking', 'ready'])
            ->when($station !== 'all', function ($query) use ($station) {
                $query->whereHas('items.menuItem', fn ($q) => $q->where('station', $station));
            })
            ->oldest('created_at')
            ->get();

        // Badge angka buat tiap tab: hitung order aktif yang punya minimal 1 item dari station itu.
        $stationCounts = [
            'all' => Order::query()->whereIn('kitchen_status', ['waiting', 'cooking', 'ready'])->count(),
        ];
        foreach (MenuItem::STATIONS as $s) {
            $stationCounts[$s] = Order::query()
                ->whereIn('kitchen_status', ['waiting', 'cooking', 'ready'])
                ->whereHas('items.menuItem', fn ($q) => $q->where('station', $s))
                ->count();
        }

        return view('Landing.kitchen', compact('orders', 'station', 'stationCounts'));
    }

    /**
     * Majukan status order ke tahap berikutnya: waiting -> cooking -> ready -> served.
     */
    public function advance(Order $order): JsonResponse
    {
        $next = $order->nextKitchenStatus();

        if (! $next) {
            return response()->json(['message' => 'Order sudah di tahap terakhir.'], 422);
        }

        $order->update(['kitchen_status' => $next]);

        return response()->json([
            'kitchen_status' => $order->kitchen_status,
        ]);
    }

    /**
     * Toggle centang "sudah disiapkan" untuk satu item pesanan.
     */
    public function toggleItem(OrderItem $orderItem): JsonResponse
    {
        $orderItem->update(['is_prepared' => ! $orderItem->is_prepared]);

        return response()->json([
            'is_prepared' => $orderItem->is_prepared,
        ]);
    }
}