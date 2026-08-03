<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller implements HasMiddleware
{
    // Contoh kode promo statis. Nanti bisa diganti jadi tabel 'promo_codes' kalau perlu dikelola dari UI.
    private const PROMO_CODES = [
        'STAFF10' => ['type' => 'percent', 'value' => 10],
        'WELCOME5' => ['type' => 'flat', 'value' => 5],
    ];

    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner,cashier'),
        ];
    }

    public function index(Request $request): View
    {
        $search = $request->query('search');

        $unpaidOrders = Order::query()
            ->with('table')
            ->where('payment_status', 'unpaid')
            ->whereIn('status', ['submitted', 'held'])
            ->oldest('created_at')
            ->get();

        $order = null;

        if ($request->filled('order')) {
            $order = Order::query()->where('payment_status', 'unpaid')->find($request->query('order'));
        } elseif ($search) {
            $order = $unpaidOrders->first(function (Order $o) use ($search) {
                $needle = strtolower($search);

                return str_contains(strtolower($o->order_number), $needle)
                    || ($o->table && str_contains(strtolower('table ' . $o->table->formatted_number), $needle));
            });
        } else {
            $order = $unpaidOrders->first();
        }

        if ($order) {
            $order->load(['table', 'items']);
        }

        return view('Landing.payments', compact('order', 'unpaidOrders', 'search'));
    }

    public function applyPromo(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'promo_code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($validated['promo_code']));
        $promo = self::PROMO_CODES[$code] ?? null;

        if (! $promo) {
            return back()->with('error', "Kode promo \"{$code}\" tidak ditemukan atau sudah tidak berlaku.");
        }

        $discount = $promo['type'] === 'percent'
            ? round(($order->subtotal + $order->tax) * $promo['value'] / 100, 2)
            : (float) $promo['value'];

        $order->update([
            'discount' => $discount,
            'promo_code' => $code,
        ]);

        return back()->with('success', "Kode promo \"{$code}\" berhasil dipakai.");
    }

    public function complete(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', Rule::in(Order::PAYMENT_METHODS)],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => $validated['payment_method'],
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Bebasin meja lagi kalau order ini sudah dibayar dan gak ada order unpaid lain di meja yang sama.
            if ($order->table) {
                $stillHasUnpaidOrders = Order::query()
                    ->where('dining_table_id', $order->dining_table_id)
                    ->where('payment_status', 'unpaid')
                    ->exists();

                if (! $stillHasUnpaidOrders) {
                    $order->table->update([
                        'status' => 'available',
                        'label' => null,
                        'subtitle' => null,
                    ]);
                }
            }
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('success', "Pembayaran order {$order->order_number} berhasil, total \${$order->amount_due}.");
    }
}