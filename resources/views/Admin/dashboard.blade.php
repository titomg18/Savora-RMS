<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f4f2] text-neutral-900">

{{--
    Data di bawah ini contoh (dummy) supaya halaman bisa langsung dilihat.
    Di project asli, ganti dengan data dari Controller, misalnya:
    return view('admin.dashboard', compact('revenueToday', 'todaysOrders', ...));
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

    $navItems = [
        ['label' => 'Dashboard',   'url' => route('dashboard'), 'active' => true],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => 'Menu', 'url' => route('admin.menu.index')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => 'Tables', 'url' => route('admin.tables.index')],
        ['label' => 'Kitchen', 'url' => route('admin.kitchen.index')],
        ['label' => 'Payments', 'url' => route('admin.payments.index')],
        ['label' => 'Inventory', 'url' => route('admin.inventory.index')],
        ['label' => 'Reports', 'url' => route('admin.reports.index')],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings', 'url' => route('admin.settings.edit')],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Header --}}
        @include('admin.partials.navbar', [
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
                    <p class="mt-3 text-sm text-emerald-600 font-medium">↗ {{ $revenueDelta }}% <span class="text-neutral-400 font-normal">vs yesterday</span></p>
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
                    <p class="mt-3 text-sm text-emerald-600 font-medium">↗ {{ $ordersDelta }}% <span class="text-neutral-400 font-normal">vs yesterday</span></p>
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

                    <div class="mt-6 overflow-x-auto">
                        <svg viewBox="0 0 760 340" class="w-full min-w-[600px] h-72">
                            <defs>
                                <linearGradient id="areaFill" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#dd6b4a" stop-opacity="0.35"/>
                                    <stop offset="100%" stop-color="#dd6b4a" stop-opacity="0"/>
                                </linearGradient>
                            </defs>

                            {{-- gridlines --}}
                            @foreach ([0, 1, 2, 3, 4, 5] as $i)
                                <line x1="40" x2="740" y1="{{ 20 + $i * 52 }}" y2="{{ 20 + $i * 52 }}" stroke="#f1e7e2" stroke-width="1"/>
                                <text x="10" y="{{ 24 + $i * 52 }}" font-size="12" fill="#9c9c9c">${{ 5 - $i }}k</text>
                            @endforeach

                            {{-- area + line (hand-tuned curve resembling the mockup) --}}
                            <path d="M40,230 C110,260 140,300 190,300 C240,300 260,180 320,150 C370,125 400,220 450,215 C500,210 520,170 570,175 C620,180 650,110 730,60 L730,280 L40,280 Z" fill="url(#areaFill)"/>
                            <path d="M40,230 C110,260 140,300 190,300 C240,300 260,180 320,150 C370,125 400,220 450,215 C500,210 520,170 570,175 C620,180 650,110 730,60" fill="none" stroke="#c0451f" stroke-width="3" stroke-linecap="round"/>

                            {{-- markers --}}
                            @foreach ([[190,300],[320,150],[450,215],[570,175],[730,60]] as $pt)
                                <circle cx="{{ $pt[0] }}" cy="{{ $pt[1] }}" r="7" fill="white" stroke="#c0451f" stroke-width="3"/>
                            @endforeach

                            {{-- x labels --}}
                            @foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $i => $day)
                                <text x="{{ 40 + $i * 115 }}" y="310" font-size="12" fill="#8a8a8a">{{ $day }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>

                {{-- Kitchen live --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="flex items-center gap-2 text-lg font-extrabold">
                            <span>🔥</span> Kitchen Live
                        </h2>
                        <span class="text-xs font-semibold bg-orange-100 text-[#c0451f] px-2.5 py-1 rounded-full">
                            {{ count($kitchenTickets) * 2 + 2 }} Active
                        </span>
                    </div>

                    <div class="mt-5 space-y-4">
                        @foreach ($kitchenTickets as $ticket)
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
                        @endforeach
                    </div>

                    <button class="mt-5 w-full rounded-lg border border-neutral-200 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                        View All Tickets
                    </button>
                </div>
            </div>

            {{-- Popular today + Recent orders --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h2 class="text-xl font-extrabold">Popular Today</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($popularToday as $dish)
                            <div class="flex items-center gap-4">
                                <img src="{{ $dish['image'] }}" alt="{{ $dish['name'] }}" class="w-14 h-14 rounded-xl object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-neutral-900">{{ $dish['name'] }}</p>
                                    <p class="text-sm text-neutral-500">{{ $dish['category'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold">{{ $dish['orders'] }} Orders</p>
                                    <p class="text-sm text-emerald-600">↑ {{ $dish['delta'] }}%</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-extrabold">Recent Orders</h2>
                        <a href="#" class="text-sm font-semibold text-[#d9603b]">View All</a>
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
                                @foreach ($recentOrders as $order)
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

</body>
</html>