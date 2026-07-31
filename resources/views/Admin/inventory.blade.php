<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

@php
    // $items (paginator), $categories, $search, $category, $status, $stats dikirim dari InventoryController@index.

    $statusMeta = [
        'in_stock'     => ['label' => 'In Stock',     'badge' => 'bg-teal-50 text-teal-700',  'value' => 'text-neutral-900'],
        'low_stock'    => ['label' => 'Low Stock',    'badge' => 'bg-amber-50 text-amber-700','value' => 'text-amber-600'],
        'out_of_stock' => ['label' => 'Out of Stock', 'badge' => 'bg-red-50 text-red-600',    'value' => 'text-red-600'],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar')

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <h2 class="text-xl font-extrabold text-[#d9603b] shrink-0">Savora RMS</h2>

            <form method="GET" action="{{ route('admin.inventory.index') }}" class="relative ml-auto w-full max-w-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search inventory..." class="w-full rounded-full border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </form>
            <div class="flex items-center gap-4">
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

        <main class="px-6 lg:px-10 py-8 bg-[#fdf2ee] min-h-[calc(100vh-77px)]">

            {{-- Page header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold">Inventory Management</h1>
                    <p class="mt-1 text-sm text-neutral-600">Track and manage your restaurant's raw materials and supplies.</p>
                </div>

                <button type="button" onclick="openInventoryModal('create')"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Update Stock
                </button>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Stat cards --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <p class="flex items-center gap-2 text-xs font-bold tracking-wide text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="7" width="18" height="13" rx="2"/>
                            <path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        </svg>
                        TOTAL ITEMS
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <p class="flex items-center gap-2 text-xs font-bold tracking-wide text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                            <path d="M12 9v4M12 17h.01"/>
                        </svg>
                        LOW STOCK ALERTS
                    </p>
                    <p class="mt-2 text-4xl font-extrabold text-red-600">{{ $stats['low_stock_alerts'] }}</p>
                </div>
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
                    <p class="flex items-center gap-2 text-xs font-bold tracking-wide text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12a9 9 0 1 1-3-6.7"/>
                            <path d="M21 3v6h-6"/>
                        </svg>
                        PENDING DELIVERIES
                    </p>
                    <p class="mt-2 text-4xl font-extrabold">{{ $stats['pending_deliveries'] }}</p>
                </div>
            </div>

            {{-- Table card --}}
            <div class="mt-6 bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">

                {{-- Filters --}}
                <form method="GET" action="{{ route('admin.inventory.index') }}" class="flex flex-wrap items-center gap-3 px-6 py-4 border-b border-neutral-100">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="category" onchange="this.form.submit()" class="rounded-lg border border-neutral-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="">All Categories</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c }}" @selected($category === $c)>{{ $c }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border border-neutral-200 px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="">All Statuses</option>
                        <option value="in_stock" @selected($status === 'in_stock')>In Stock</option>
                        <option value="low_stock" @selected($status === 'low_stock')>Low Stock</option>
                        <option value="out_of_stock" @selected($status === 'out_of_stock')>Out of Stock</option>
                    </select>

                    @if ($category || $status || $search)
                        <a href="{{ route('admin.inventory.index') }}" class="text-sm font-semibold text-neutral-500 hover:text-neutral-700">Reset</a>
                    @endif

                    <span class="ml-auto inline-flex items-center gap-1.5 text-sm text-neutral-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3Z"/>
                        </svg>
                        Filter
                    </span>
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-neutral-500">
                                <th class="px-6 py-4 font-semibold">Ingredient Name</th>
                                <th class="px-6 py-4 font-semibold">Category</th>
                                <th class="px-6 py-4 font-semibold text-right">Current Stock</th>
                                <th class="px-6 py-4 font-semibold text-right">Minimum Stock</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                @php $meta = $statusMeta[$item->stock_status]; @endphp
                                <tr class="border-t border-neutral-100 {{ $item->stock_status === 'out_of_stock' ? 'bg-red-50/30' : '' }}">
                                    <td class="px-6 py-4 font-bold">{{ $item->name }}</td>
                                    <td class="px-6 py-4 text-neutral-600">{{ $item->category }}</td>
                                    <td class="px-6 py-4 text-right font-semibold {{ $meta['value'] }}">
                                        {{ number_format($item->current_stock, 1) }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-neutral-600">
                                        {{ number_format($item->minimum_stock, 1) }} {{ $item->unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-extrabold tracking-wide {{ $meta['badge'] }}">
                                            {{ strtoupper($meta['label']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                onclick='openInventoryModal("edit", {{ $item->id }}, {{ Illuminate\Support\Js::from([
                                                    "name" => $item->name,
                                                    "category" => $item->category,
                                                    "unit" => $item->unit,
                                                    "current_stock" => (float) $item->current_stock,
                                                    "minimum_stock" => (float) $item->minimum_stock,
                                                ]) }})'
                                                class="inline-flex items-center justify-center rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-700 w-9 h-9"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.inventory.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this inventory item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 w-9 h-9">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                        <path d="M10 11v6M14 11v6"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-neutral-500">
                                        Belum ada item inventory. Klik "Update Stock" untuk menambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-neutral-100">
                    <p class="text-sm text-neutral-500">
                        Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items
                    </p>
                    <div>
                        {{ $items->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- ===================== ADD / EDIT INVENTORY MODAL ===================== --}}
<div id="inventoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <h2 id="inventoryModalTitle" class="text-lg font-extrabold">Update Stock</h2>
            <button type="button" onclick="closeInventoryModal()" class="text-neutral-400 hover:text-neutral-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="inventoryForm" action="{{ route('admin.inventory.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div id="inventoryMethodField"></div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Ingredient Name</label>
                <input id="inventoryNameInput" type="text" name="name" required placeholder="e.g. Roma Tomatoes"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Category</label>
                <input id="inventoryCategoryInput" type="text" name="category" list="inventoryCategoryOptions" required placeholder="e.g. Produce"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                <datalist id="inventoryCategoryOptions">
                    @foreach (\App\Models\InventoryItem::CATEGORIES as $c)
                        <option value="{{ $c }}"></option>
                    @endforeach
                </datalist>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Current Stock</label>
                    <input id="inventoryCurrentStockInput" type="number" name="current_stock" step="0.01" min="0" required
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Minimum Stock</label>
                    <input id="inventoryMinimumStockInput" type="number" name="minimum_stock" step="0.01" min="0" required
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Unit</label>
                    <select id="inventoryUnitInput" name="unit" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        @foreach (\App\Models\InventoryItem::UNITS as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeInventoryModal()"
                        class="flex-1 rounded-lg border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] px-4 py-2.5 text-sm font-semibold text-white">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const inventoryIndexUrl = @js(route('admin.inventory.index'));

    function openInventoryModal(mode, id = null, data = {}) {
        const form = document.getElementById('inventoryForm');
        const title = document.getElementById('inventoryModalTitle');
        const methodField = document.getElementById('inventoryMethodField');

        document.getElementById('inventoryNameInput').value = data.name ?? '';
        document.getElementById('inventoryCategoryInput').value = data.category ?? '';
        document.getElementById('inventoryUnitInput').value = data.unit ?? 'kg';
        document.getElementById('inventoryCurrentStockInput').value = data.current_stock ?? '';
        document.getElementById('inventoryMinimumStockInput').value = data.minimum_stock ?? '';

        if (mode === 'edit') {
            title.textContent = 'Edit Stock';
            form.action = `${inventoryIndexUrl}/${id}`;
            methodField.innerHTML = '@method('PUT')';
        } else {
            title.textContent = 'Update Stock';
            form.action = inventoryIndexUrl;
            methodField.innerHTML = '';
        }

        document.getElementById('inventoryModal').classList.remove('hidden');
    }

    function closeInventoryModal() {
        document.getElementById('inventoryModal').classList.add('hidden');
    }

    @if ($errors->any())
        openInventoryModal('create');
    @endif
</script>

</body>
</html>