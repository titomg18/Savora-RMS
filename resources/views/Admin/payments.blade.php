<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f1f5] text-neutral-900">

@php
    // $order (nullable, dengan relasi table+items), $unpaidOrders, $search dikirim dari PaymentController@index.
    $navItems = [
        ['label' => 'Dashboard',   'url' => route('dashboard')],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => 'Menu', 'url' => route('admin.menu.index')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => 'Tables', 'url' => route('admin.tables.index')],
        ['label' => 'Kitchen', 'url' => route('admin.kitchen.index')],
        ['label' => 'Payments', 'url' => route('admin.payments.index'), 'active' => true],
        ['label' => 'Inventory', 'url' => route('admin.inventory.index')],
        ['label' => 'Reports', 'url' => route('admin.reports.index')],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings', 'url' => route('admin.settings.edit')],
    ];

    $methodMeta = [
        'cash' => ['label' => 'Cash', 'icon' => 'cash'],
        'card' => ['label' => 'Debit / Credit', 'icon' => 'card'],
        'qris' => ['label' => 'QRIS', 'icon' => 'qris'],
        'ewallet' => ['label' => 'E-Wallet', 'icon' => 'ewallet'],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <form method="GET" action="{{ route('admin.payments.index') }}" class="relative w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search orders..." class="w-full rounded-full border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </form>
            <div class="ml-auto flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 4-6 8-6s8 2 8 6"/>
                </svg>
            </div>
        </header>

        <main class="px-6 lg:px-10 py-8 bg-[#f4f1f5] min-h-[calc(100vh-77px)]">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if (! $order)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-16 text-center text-neutral-500">
                    <p class="text-lg font-bold text-neutral-700">Tidak ada order unpaid ditemukan.</p>
                    <p class="mt-1 text-sm">Semua order sudah dibayar, atau coba kata kunci pencarian lain.</p>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-[1fr_420px] gap-6 items-start">

                    {{-- ===================== LEFT: BILL ===================== --}}
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                        <div class="flex items-start justify-between flex-wrap gap-3">
                            <div>
                                <h1 class="text-3xl font-extrabold">
                                    {{ $order->table ? 'Table ' . $order->table->formatted_number : 'Takeout Order' }}
                                </h1>
                                <p class="mt-1 text-sm font-mono text-neutral-500">Order {{ $order->order_number }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-neutral-100 text-neutral-700 px-3.5 py-1.5 text-sm font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    {{ $order->guests }} Guests
                                </span>
                                <span class="inline-flex items-center rounded-full px-3.5 py-1.5 text-sm font-semibold
                                    {{ $order->payment_status === 'paid' ? 'bg-teal-50 text-teal-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $order->payment_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 border-t border-neutral-100">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-neutral-500">
                                        <th class="pt-5 pb-3 font-semibold">Item</th>
                                        <th class="pt-5 pb-3 font-semibold text-center">Qty</th>
                                        <th class="pt-5 pb-3 font-semibold text-right">Price</th>
                                        <th class="pt-5 pb-3 font-semibold text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr class="border-t border-neutral-100">
                                            <td class="py-4">
                                                <p class="font-bold">{{ $item->name }}</p>
                                                @if ($item->note)
                                                    <p class="text-neutral-500">{{ $item->note }}</p>
                                                @endif
                                            </td>
                                            <td class="py-4 text-center">{{ $item->quantity }}</td>
                                            <td class="py-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="py-4 text-right font-bold">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 pt-5 border-t border-neutral-100 space-y-2 text-sm max-w-xs ml-auto">
                            <div class="flex justify-between text-neutral-600">
                                <span>Subtotal</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-neutral-600">
                                <span>Tax ({{ $order->subtotal > 0 ? rtrim(rtrim(number_format($order->tax / $order->subtotal * 100, 2), '0'), '.') : '0' }}%)</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[#d9603b] font-semibold">
                                <span>Discount{{ $order->promo_code ? ' (' . $order->promo_code . ')' : '' }}</span>
                                <span>-${{ number_format($order->discount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ===================== RIGHT: PAYMENT ===================== --}}
                    <div
                        x-data="{ method: @js($order->payment_method ?? 'card') }"
                        class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6"
                    >
                        <div class="rounded-xl bg-orange-50 px-5 py-6 text-center">
                            <p class="text-xs font-semibold tracking-wide text-neutral-500">AMOUNT DUE</p>
                            <p class="mt-1 text-4xl font-extrabold text-[#d9603b]">${{ number_format($order->amount_due, 2) }}</p>
                        </div>

                        {{-- Promo code --}}
                        <form action="{{ route('admin.payments.apply_promo', $order) }}" method="POST" class="mt-6">
                            @csrf
                            <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Apply Discount / Promo Code</label>
                            <div class="flex items-center gap-2">
                                <input type="text" name="promo_code" placeholder="e.g. STAFF10"
                                       class="flex-1 rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                <button type="submit" class="rounded-lg bg-neutral-100 hover:bg-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700">
                                    Apply
                                </button>
                            </div>
                        </form>

                        {{-- Payment method --}}
                        <form id="paymentForm" action="{{ route('admin.payments.complete', $order) }}" method="POST" class="mt-7">
                            @csrf
                            <input type="hidden" name="payment_method" x-model="method">

                            <p class="font-extrabold">Payment Method</p>
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                @foreach ($methodMeta as $key => $meta)
                                    <button
                                        type="button"
                                        @click="method = '{{ $key }}'"
                                        :class="method === '{{ $key }}' ? 'border-[#dd6b4a] bg-orange-50' : 'border-neutral-200 hover:bg-neutral-50'"
                                        class="relative rounded-xl border-2 px-4 py-5 flex flex-col items-center gap-2 text-sm font-semibold text-neutral-800"
                                    >
                                        <span x-show="method === '{{ $key }}'" class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-[#dd6b4a]"></span>

                                        @if ($key === 'cash')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#d9603b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        @elseif ($key === 'card')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#d9603b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="2" y="5" width="20" height="14" rx="2"/>
                                                <path d="M2 10h20"/>
                                            </svg>
                                        @elseif ($key === 'qris')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#d9603b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                                                <path d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20v.01"/>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#d9603b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                                <rect x="2" y="4" width="17" height="16" rx="2"/>
                                                <path d="M19 9h2a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1h-2"/>
                                                <circle cx="15" cy="13" r="1"/>
                                            </svg>
                                        @endif
                                        {{ $meta['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </form>

                        <div class="mt-8 pt-5 border-t border-neutral-100 flex items-center gap-3">
                            <button type="button" onclick="window.print()"
                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-[#dd6b4a] text-[#d9603b] px-4 py-3 text-sm font-semibold hover:bg-orange-50">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9V2h12v7"/>
                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                    <path d="M6 14h12v8H6z"/>
                                </svg>
                                Print Receipt
                            </button>
                            <button type="submit" form="paymentForm"
                                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-3 text-sm font-bold">
                                Complete Payment
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Other unpaid orders --}}
                @if ($unpaidOrders->count() > 1)
                    <div class="mt-8">
                        <p class="text-sm font-semibold text-neutral-500 mb-3">Order unpaid lainnya</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($unpaidOrders as $o)
                                @continue($o->id === $order->id)
                                <a href="{{ route('admin.payments.index', ['order' => $o->id]) }}"
                                   class="rounded-lg bg-white border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 hover:border-[#dd6b4a] hover:text-[#d9603b]">
                                    {{ $o->table ? 'Table ' . $o->table->formatted_number : $o->order_number }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </main>
    </div>
</div>

</body>
</html>