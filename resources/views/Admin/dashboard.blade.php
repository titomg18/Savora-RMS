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
        ['label' => 'Dashboard',   'active' => true],
        ['label' => 'Orders'],
        ['label' => 'Menu'],
        ['label' => 'Categories'],
        ['label' => 'Tables'],
        ['label' => 'Kitchen'],
        ['label' => 'Payments'],
        ['label' => 'Inventory'],
        ['label' => 'Reports'],
        ['label' => 'Users'],
        ['label' => 'Settings'],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-[#fdf2ee] border-r border-orange-100 px-5 py-6">
        <a href="{{ route('dashboard') ?? '#' }}" class="flex items-center gap-2 px-2">
            <span class="text-xl">🍴</span>
            <div class="leading-tight">
                <p class="font-extrabold text-[#d9603b] text-lg">Savora RMS</p>
                <p class="text-xs text-neutral-500">Management Suite</p>
            </div>
        </a>

        <nav class="mt-8 flex-1 space-y-1">
            @foreach ($navItems as $item)
                <a
                    href="#"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                        {{ $item['active'] ?? false
                            ? 'bg-white text-[#d9603b] shadow-sm'
                            : 'text-neutral-600 hover:bg-white/60' }}"
                >
                    <span class="w-5 h-5 flex items-center justify-center">
                        {{-- simple dot as generic icon placeholder; swap with heroicons per item if you like --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        </svg>
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="pt-4 border-t border-orange-100">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-neutral-600 hover:bg-white/60 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <path d="M16 17l5-5-5-5"/>
                    <path d="M21 12H9"/>
                </svg>
                Logout
            </button>
        </form>
    </aside>

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Header --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between px-6 lg:px-10 py-6 bg-[#fdf2ee] border-b border-orange-100">
            <div>
                <h1 class="text-3xl font-extrabold">Overview</h1>
                <p class="mt-1 text-sm text-neutral-600">
                    Welcome back, {{ auth()->user()->name ?? 'Admin' }}. Here's what's happening today.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="relative hidden md:block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </span>
                    <input
                        type="text"
                        placeholder="Search orders, items..."
                        class="w-72 rounded-full border border-neutral-200 bg-white pl-10 pr-4 py-2.5 text-sm placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30"
                    >
                </div>

                <button class="relative w-10 h-10 flex items-center justify-center rounded-full bg-white border border-neutral-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                </button>

                <button class="hidden sm:inline-flex items-center gap-1.5 rounded-lg border border-[#d9603b] text-[#d9603b] px-4 py-2.5 text-sm font-semibold hover:bg-orange-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
                    </svg>
                    Add Menu
                </button>

                <button class="inline-flex items-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Create Order
                </button>
            </div>
        </header>

        <main class="px-6 lg:px-10 py-8 space-y-8">

            {{-- Stat cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <p class="text-xs font-semibold tracking-wide text-neutral-500 uppercase">Revenue Today</p>
                        <span class="w-9 h-9 rounded-full bg-orange-100 text-[#d9603b] flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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