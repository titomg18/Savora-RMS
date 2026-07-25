<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller implements HasMiddleware
{
    private const TAX_RATE = 0.085; // 8.5%

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function index(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        $menuItems = MenuItem::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $tables = DiningTable::query()
            ->orderBy('area')
            ->orderBy('number')
            ->get();

        return view('Admin.orders', compact('categories', 'menuItems', 'tables'));
    }

    /**
     * Submit atau hold order dari POS builder. Dikirim sebagai JSON dari Alpine.js.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dining_table_id' => ['nullable', 'exists:dining_tables,id'],
            'guests' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['required', Rule::in(['held', 'submitted'])],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'items.*.note' => ['nullable', 'string', 'max:255'],
        ]);

        $order = DB::transaction(function () use ($validated, $request) {
            $subtotal = 0;
            $lineItems = [];

            foreach ($validated['items'] as $line) {
                $menuItem = MenuItem::findOrFail($line['menu_item_id']);
                $lineTotal = $menuItem->price * $line['quantity'];
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'unit_price' => $menuItem->price,
                    'quantity' => $line['quantity'],
                    'note' => $line['note'] ?? null,
                ];
            }

            $tax = round($subtotal * self::TAX_RATE, 2);
            $total = $subtotal + $tax;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'dining_table_id' => $validated['dining_table_id'] ?? null,
                'user_id' => $request->user()?->id,
                'guests' => $validated['guests'],
                'status' => $validated['status'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
            ]);

            foreach ($lineItems as $item) {
                $order->items()->create($item);
            }

            if ($order->table) {
                $order->table->update([
                    'status' => 'occupied',
                    'subtitle' => $order->order_number,
                ]);
            }

            return $order;
        });

        return response()->json([
            'message' => $validated['status'] === 'held'
                ? "Order {$order->order_number} berhasil di-hold."
                : "Order {$order->order_number} berhasil disubmit.",
            'order_number' => $order->order_number,
        ]);
    }
}