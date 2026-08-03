<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f4f2] text-neutral-900">

{{--
    Data di bawah ini contoh (dummy) supaya halaman bisa langsung dilihat.
    Di project asli, ganti dengan data dari Controller, misalnya:
    return view('Landing.dashboard', compact('revenueToday', 'todaysOrders', ...));
--}}
@php
    $revenueToday   = $revenueToday   ?? 4289.00;
    $revenueDelta   = $revenueDelta   ?? 12.5;
    $todaysOrders   = $todaysOrders   ?? 142;
    $ordersDelta    = $ordersDelta    ?? 4.2;
    $activeTables   = $activeTables   ?? 18;
    $tableCapacity  = $tableCapacity  ?? 75;
    $availableTables = $availableTables ?? 6;
    $totalTables    = $totalTables    ?? 8;
    $activeKitchenCount = $activeKitchenCount ?? 6;
    $weeklyRevenue = $weeklyRevenue ?? collect(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'])
        ->map(fn ($day) => ['label' => $day, 'value' => rand(1800, 4800)]);

    $kitchenTickets = $kitchenTickets ?? [
        ['id' => '#1042', 'place' => 'Table 4', 'time' => '24m', 'urgency' => 'high', 'items' => [
            ['qty' => 2, 'name' => 'Truffle Burger', 'note' => null],
            ['qty' => 1, 'name' => 'Caesar Salad', 'note' => 'No croutons'],
        ]],
        ['id' => '#1043', 'place' => 'Takeaway', 'time' => '12m', 'urgency' => 'mid', 'items' => [
            ['qty' => 1, 'name' => 'Margherita Pizza', 'note' => null],
            ['qty' => 2, 'name' => 'Garlic Bread', 'note' => null],
        ]],
        ['id' => '#1044', 'place' => 'Table 12', 'time' => '5m', 'urgency' => 'low', 'items' => [
            ['qty' => 3, 'name' => 'Ribeye Steak', 'note' => null],
            ['qty' => 1, 'name' => 'House Wine (Btl)', 'note' => null],
        ]],
    ];

    $popularToday = $popularToday ?? [
        ['name' => 'Truffle Burger', 'category' => 'Main Course', 'orders' => 42, 'delta' => 12, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=200&auto=format&fit=crop'],
        ['name' => 'Margherita Pizza', 'category' => 'Mains', 'orders' => 38, 'delta' => 5, 'image' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?q=80&w=200&auto=format&fit=crop'],
    ];

    $recentOrders = $recentOrders ?? [
        ['id' => '#1041', 'table' => 'Table 8', 'total' => 124.50, 'status' => 'Paid'],
        ['id' => '#1040', 'table' => 'Takeaway', 'total' => 45.00, 'status' => 'Paid'],
    ];

@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('Landing.partials.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col min-w-0">
        @include('Landing.partials.navbar', [
            'pageTitle' => 'Overview',
            'pageSubtitle' => "Welcome back, " . (auth()->user()->name ?? 'Admin') . ". Here's what's happening today.",
        ])

        <main class="px-6 lg:px-10 py-8 space-y-8">

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold tracking-wide text-neutral-500 uppercase">Revenue Today</p>
                        <span class="w-9 h-9 rounded-full bg-orange-100 text-[#d9603b] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                <circle cx="12" cy="12" r="2.5"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold">${{ number_format($revenueToday, 2) }}</p>
                    <p class="mt-3 text-sm font-medium {{ $revenueDelta < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                        {{ $revenueDelta < 0 ? '↘' : '↗' }} {{ abs($revenueDelta) }}% <span class="text-neutral-400 font-normal">vs yesterday</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold tracking-wide text-neutral-500 uppercase">Today's Orders</p>
                        <span class="w-9 h-9 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="4" y="3" width="16" height="18" rx="2"/>
                                <path d="M8 7h8M8 11h8M8 15h5"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold">{{ $todaysOrders }}</p>
                    <p class="mt-3 text-sm font-medium {{ $ordersDelta < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                        {{ $ordersDelta < 0 ? '↘' : '↗' }} {{ abs($ordersDelta) }}% <span class="text-neutral-400 font-normal">vs yesterday</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold tracking-wide text-neutral-500 uppercase">Active Tables</p>
                        <span class="w-9 h-9 rounded-full bg-orange-100 text-[#d9603b] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="9" cy="8" r="3"/>
                                <path d="M2 21v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1"/>
                                <circle cx="18" cy="9" r="2.2"/>
                                <path d="M16.5 21v-1a3.5 3.5 0 0 1 3-3.46"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold">{{ $activeTables }}</p>
                    <div class="mt-4">
                        <div class="h-1.5 rounded-full bg-orange-100 overflow-hidden">
                            <div class="h-full bg-[#c0451f]" style="width: {{ $tableCapacity }}%"></div>
                        </div>
                        <p class="mt-1.5 text-xs text-neutral-500">{{ $tableCapacity }}% Capacity</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold tracking-wide text-neutral-500 uppercase">Available Tables</p>
                        <span class="w-9 h-9 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 10h18M5 10V6a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v4M5 10v10M19 10v10"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold">{{ $availableTables }}</p>
                    <div class="mt-4 flex gap-1">
                        @for ($i = 0; $i < $totalTables; $i++)
                            <span class="h-2.5 flex-1 rounded-full {{ $i < $availableTables ? 'bg-emerald-600' : 'bg-orange-100' }}"></span>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- Chart + Kitchen live --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Revenue trends --}}
                <div class="xl:col-span-2 bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold">Revenue Trends</h2>
                        <div class="flex items-center bg-orange-50 rounded-full p-1 text-sm font-medium">
                            <button class="px-3 py-1.5 rounded-full bg-white shadow-sm text-neutral-900">Weekly</button>
                            <button class="px-3 py-1.5 rounded-full text-neutral-500">Monthly</button>
                        </div>
                    </div>

                    <div class="mt-6">
                        <canvas id="revenueTrendChart" height="280"></canvas>
                    </div>
                </div>

                {{-- Kitchen live --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center gap-2 text-lg font-extrabold">
                            <span>🔥</span> Kitchen Live
                        </h2>
                        <span class="text-xs font-semibold bg-orange-100 text-[#c0451f] px-2.5 py-1 rounded-full">
                            {{ $activeKitchenCount }} Active
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse ($kitchenTickets as $ticket)
                            @php
                                $borderColor = match($ticket['urgency']) {
                                    'high' => 'border-l-red-500',
                                    'mid'  => 'border-l-orange-400',
                                    default => 'border-l-emerald-500',
                                };
                                $timeColor = match($ticket['urgency']) {
                                    'high' => 'text-red-500',
                                    'mid'  => 'text-orange-500',
                                    default => 'text-emerald-600',
                                };
                            @endphp
                            <div class="border border-neutral-100 border-l-4 {{ $borderColor }} rounded-xl p-4 bg-neutral-50/50">
                                <div class="flex items-center justify-between text-sm font-bold">
                                    <span>{{ $ticket['id'] }} • {{ $ticket['place'] }}</span>
                                    <span class="flex items-center gap-1 font-semibold {{ $timeColor }}">
                                        ⏱ {{ $ticket['time'] }}
                                    </span>
                                </div>
                                <ul class="mt-2 space-y-1 text-sm text-neutral-700">
                                    @foreach ($ticket['items'] as $item)
                                        <li>
                                            <span class="font-semibold">{{ $item['qty'] }}x</span> {{ $item['name'] }}
                                            @if ($item['note'])
                                                <span class="text-[#c0451f]">({{ $item['note'] }})</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-400 text-center py-6">Gak ada order aktif di dapur saat ini. 🎉</p>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.kitchen.index') }}" class="mt-5 block w-full rounded-lg border border-neutral-200 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50 text-center">
                        View All Tickets
                    </a>
                </div>
            </div>

            {{-- Popular today + Recent orders --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h2 class="text-xl font-extrabold">Popular Today</h2>
                    <div class="mt-5 space-y-4">
                        @forelse ($popularToday as $dish)
                            <div class="flex items-center gap-4">
                                @if ($dish['image'])
                                    <img src="{{ $dish['image'] }}" alt="{{ $dish['name'] }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-neutral-100 flex items-center justify-center shrink-0 text-neutral-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                                            <circle cx="9" cy="9" r="2"/>
                                            <path d="m21 15-5-5L5 21"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-neutral-900">{{ $dish['name'] }}</p>
                                    <p class="text-sm text-neutral-500">{{ $dish['category'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">{{ $dish['orders'] }} Orders</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-400 text-center py-6">Belum ada penjualan hari ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold">Recent Orders</h2>
                        <a href="{{ route('admin.reports.items') }}" class="text-sm font-semibold text-[#d9603b]">View All</a>
                    </div>

                    <div class="mt-5 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-neutral-500 border-b border-neutral-100">
                                    <th class="pb-3 font-medium">Order ID</th>
                                    <th class="pb-3 font-medium">Table</th>
                                    <th class="pb-3 font-medium">Total</th>
                                    <th class="pb-3 font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentOrders as $order)
                                    <tr class="border-b border-neutral-50 last:border-0">
                                        <td class="py-3.5 font-semibold">{{ $order['id'] }}</td>
                                        <td class="py-3.5 text-neutral-600">{{ $order['table'] }}</td>
                                        <td class="py-3.5 font-semibold">${{ number_format($order['total'], 2) }}</td>
                                        <td class="py-3.5">
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 text-emerald-700 px-3 py-1 text-xs font-semibold">
                                                {{ $order['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-neutral-400">Belum ada order yang dibayar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    const revenueLabels = @json($weeklyRevenue->pluck('label'));
    const revenueValues = @json($weeklyRevenue->pluck('value'));

    const ctx = document.getElementById('revenueTrendChart');
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
    gradient.addColorStop(0, 'rgba(221, 107, 74, 0.35)');
    gradient.addColorStop(1, 'rgba(221, 107, 74, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                data: revenueValues,
                borderColor: '#c0451f',
                backgroundColor: gradient,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#c0451f',
                pointBorderWidth: 3,
                pointRadius: 5,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: (c) => '$' + c.parsed.y.toLocaleString() } },
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => '$' + (v >= 1000 ? (v / 1000) + 'k' : v) }, grid: { color: '#f1e7e2' } },
                x: { grid: { display: false } },
            },
        },
    });
</script>

</body>
</html>