<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

@php
    // $start, $end, $granularity, $revenue, $revenueChange, $transactions, $transactionsChange,
    // $avgOrderValue, $avgOrderValueChange, $bestCategory, $bestCategoryPercent,
    // $categoryChart, $revenueSeries, $bestSellers dikirim dari ReportController@index.
    $navItems = [
        ['label' => 'Dashboard',   'url' => route('dashboard')],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => 'Menu', 'url' => route('admin.menu.index')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => 'Tables', 'url' => route('admin.tables.index')],
        ['label' => 'Kitchen', 'url' => route('admin.kitchen.index')],
        ['label' => 'Payments', 'url' => route('admin.payments.index')],
        ['label' => 'Inventory', 'url' => route('admin.inventory.index')],
        ['label' => 'Reports', 'url' => route('admin.reports.index'), 'active' => true],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings', 'url' => route('admin.settings.edit')],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <div class="relative w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search reports..." class="w-full rounded-full border border-orange-100 bg-[#fdf1ea] pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>
            <div class="ml-auto flex items-center gap-4">
                <span class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 rounded-full bg-red-500"></span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 20c0-4 4-6 8-6s8 2 8 6"/>
                </svg>
            </div>
        </header>

        <main class="px-6 lg:px-10 py-8 bg-[#fdf2ee] min-h-[calc(100vh-77px)]">

            {{-- Page header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold">Reports</h1>
                    <p class="mt-1 text-sm text-neutral-600">Overview of performance and sales data.</p>
                </div>

                <div class="flex items-center gap-3" x-data="{ pickerOpen: false }">
                    <div class="relative">
                        <button type="button" @click="pickerOpen = !pickerOpen" @click.outside="pickerOpen = false"
                                class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 bg-white px-4 py-2.5 text-sm font-semibold text-neutral-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                            {{ $start->format('M j, Y') }} - {{ $end->format('M j, Y') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        <form method="GET" action="{{ route('admin.reports.index') }}"
                              x-show="pickerOpen" x-cloak
                              class="absolute right-0 mt-2 w-72 bg-white rounded-xl border border-neutral-200 shadow-lg p-4 z-20 space-y-3">
                            <input type="hidden" name="granularity" value="{{ $granularity }}">
                            <div>
                                <label class="block text-xs font-semibold text-neutral-500 mb-1">Start</label>
                                <input type="date" name="start" value="{{ $start->format('Y-m-d') }}" class="w-full rounded-lg border border-neutral-200 px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-neutral-500 mb-1">End</label>
                                <input type="date" name="end" value="{{ $end->format('Y-m-d') }}" class="w-full rounded-lg border border-neutral-200 px-3 py-2 text-sm">
                            </div>
                            <button type="submit" class="w-full rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white text-sm font-semibold py-2.5">
                                Apply
                            </button>
                        </form>
                    </div>

                    <div class="relative" x-data="{ exportOpen: false }">
                        <button type="button" @click="exportOpen = !exportOpen" @click.outside="exportOpen = false"
                                class="inline-flex items-center gap-2 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 15V3M6 11l6 6 6-6"/>
                                <path d="M4 21h16"/>
                            </svg>
                            Export
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>
                        <div x-show="exportOpen" x-cloak class="absolute right-0 mt-2 w-44 bg-white rounded-xl border border-neutral-200 shadow-lg py-2 z-20">
                            <a href="{{ route('admin.reports.export', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')]) }}"
                               class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50">Export as CSV</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stat cards --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-500">Monthly Revenue</p>
                        <span class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-[#d9603b]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="6" width="20" height="12" rx="2"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold">${{ number_format($revenue, 0) }}</p>
                    <p class="mt-1.5 text-sm flex items-center gap-1 {{ $revenueChange['direction'] === 'down' ? 'text-red-500' : 'text-teal-600' }}">
                        @include('Admin.partials.trend-arrow', ['direction' => $revenueChange['direction']])
                        {{ $revenueChange['percent'] }}% <span class="text-neutral-400">vs last period</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-500">Total Transactions</p>
                        <span class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-[#d9603b]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 2h6l1 4H8l1-4Z"/>
                                <path d="M5 6h14l-1 15H6L5 6Z"/>
                                <path d="M9 11h6M9 15h6"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold">{{ number_format($transactions) }}</p>
                    <p class="mt-1.5 text-sm flex items-center gap-1 {{ $transactionsChange['direction'] === 'down' ? 'text-red-500' : 'text-teal-600' }}">
                        @include('Admin.partials.trend-arrow', ['direction' => $transactionsChange['direction']])
                        {{ $transactionsChange['percent'] }}% <span class="text-neutral-400">vs last period</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-500">Average Order Value</p>
                        <span class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-[#d9603b]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                <path d="M3 6h18"/>
                                <path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-3xl font-extrabold">${{ number_format($avgOrderValue, 2) }}</p>
                    <p class="mt-1.5 text-sm flex items-center gap-1 {{ $avgOrderValueChange['direction'] === 'down' ? 'text-red-500' : 'text-teal-600' }}">
                        @include('Admin.partials.trend-arrow', ['direction' => $avgOrderValueChange['direction']])
                        {{ $avgOrderValueChange['percent'] }}% <span class="text-neutral-400">vs last period</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-neutral-500">Best Selling Category</p>
                        <span class="w-9 h-9 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m12 2 3.1 6.3 6.9 1-5 4.9L18.2 21 12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1L12 2Z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="mt-3 text-2xl font-extrabold">{{ $bestCategory->name ?? '—' }}</p>
                    <p class="mt-1.5 text-sm text-neutral-500">{{ $bestCategoryPercent }}% of total revenue</p>
                </div>
            </div>

            {{-- Chart + donut --}}
            <div class="mt-6 grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 items-start">

                {{-- Revenue Over Time --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-extrabold">Revenue Over Time</h2>
                        <div class="inline-flex rounded-lg bg-neutral-100 p-1 text-sm font-semibold">
                            <a href="{{ request()->fullUrlWithQuery(['granularity' => 'daily']) }}"
                               class="px-3.5 py-1.5 rounded-md {{ $granularity === 'daily' ? 'bg-white shadow text-neutral-900' : 'text-neutral-500' }}">Daily</a>
                            <a href="{{ request()->fullUrlWithQuery(['granularity' => 'weekly']) }}"
                               class="px-3.5 py-1.5 rounded-md {{ $granularity === 'weekly' ? 'bg-white shadow text-neutral-900' : 'text-neutral-500' }}">Weekly</a>
                        </div>
                    </div>
                    <div class="mt-4 h-72">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Sales by Category --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <h2 class="text-lg font-extrabold">Sales by Category</h2>
                    <div class="mt-4 relative h-56 flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-xs text-neutral-500">Total</p>
                            <p class="text-2xl font-extrabold">{{ number_format($transactions) }}</p>
                        </div>
                    </div>
                    <div class="mt-5 space-y-2.5">
                        @forelse ($categoryChart as $c)
                            <div class="flex items-center justify-between text-sm">
                                <span class="flex items-center gap-2 text-neutral-700">
                                    <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $c['color'] }}"></span>
                                    {{ $c['name'] }}
                                </span>
                                <span class="font-semibold">{{ $c['percent'] }}%</span>
                            </div>
                        @empty
                            <p class="text-sm text-neutral-400">Belum ada data penjualan di rentang tanggal ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Best Selling Menu Items --}}
            <div class="mt-6 bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between px-6 py-5">
                    <h2 class="text-lg font-extrabold">Best Selling Menu Items</h2>
                    <a href="{{ route('admin.reports.items', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')]) }}"
                       class="text-sm font-semibold text-[#d9603b] hover:underline">View All Items</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-neutral-500 border-t border-neutral-100">
                                <th class="px-6 py-3 font-semibold">Item Name</th>
                                <th class="px-6 py-3 font-semibold">Category</th>
                                <th class="px-6 py-3 font-semibold text-right">Units Sold</th>
                                <th class="px-6 py-3 font-semibold text-right">Revenue</th>
                                <th class="px-6 py-3 font-semibold text-right">Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bestSellers as $row)
                                <tr class="border-t border-neutral-100">
                                    <td class="px-6 py-4 font-bold">{{ $row['name'] }}</td>
                                    <td class="px-6 py-4 text-neutral-600">{{ $row['category'] }}</td>
                                    <td class="px-6 py-4 text-right">{{ number_format($row['units_sold']) }}</td>
                                    <td class="px-6 py-4 text-right">${{ number_format($row['revenue'], 2) }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold
                                            {{ match ($row['trend_direction']) {
                                                'up' => 'bg-teal-50 text-teal-700',
                                                'down' => 'bg-red-50 text-red-600',
                                                default => 'bg-neutral-100 text-neutral-500',
                                            } }}">
                                            @include('Admin.partials.trend-arrow', ['direction' => $row['trend_direction']])
                                            {{ $row['trend_percent'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                                        Belum ada penjualan tercatat di rentang tanggal ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    const revenueLabels = @json(array_column($revenueSeries, 'label'));
    const revenueValues = @json(array_column($revenueSeries, 'value'));
    const maxIndex = revenueValues.indexOf(Math.max(...revenueValues));

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                data: revenueValues,
                backgroundColor: revenueValues.map((_, i) => i === maxIndex ? '#9a3412' : '#e8845f'),
                borderRadius: 6,
                maxBarThickness: 56,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: (ctx) => '$' + ctx.parsed.y.toLocaleString() } } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: (v) => '$' + (v >= 1000 ? (v / 1000) + 'k' : v) }, grid: { color: '#f1f1f1' } },
                x: { grid: { display: false } },
            },
        },
    });

    const categoryData = @json($categoryChart);
    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: categoryData.map((c) => c.name),
            datasets: [{
                data: categoryData.map((c) => c.revenue),
                backgroundColor: categoryData.map((c) => c.color),
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } },
        },
    });
</script>

</body>
</html>