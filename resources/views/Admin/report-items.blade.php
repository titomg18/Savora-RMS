<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Best Selling Items | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

@php
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
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    <div class="flex-1 min-w-0">
        <main class="px-6 lg:px-10 py-8">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('admin.reports.index', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')]) }}"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-neutral-200 bg-white hover:bg-neutral-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold">All Best Selling Items</h1>
                    <p class="text-sm text-neutral-500">{{ $start->format('M j, Y') }} - {{ $end->format('M j, Y') }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-neutral-500">
                            <th class="px-6 py-4 font-semibold">#</th>
                            <th class="px-6 py-4 font-semibold">Item Name</th>
                            <th class="px-6 py-4 font-semibold">Category</th>
                            <th class="px-6 py-4 font-semibold text-right">Units Sold</th>
                            <th class="px-6 py-4 font-semibold text-right">Revenue</th>
                            <th class="px-6 py-4 font-semibold text-right">Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bestSellers as $i => $row)
                            <tr class="border-t border-neutral-100">
                                <td class="px-6 py-4 text-neutral-400">{{ $i + 1 }}</td>
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
                                <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                                    Belum ada penjualan tercatat di rentang tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

</body>
</html>