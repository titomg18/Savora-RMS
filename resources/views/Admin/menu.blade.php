<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar')

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <div class="relative w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search RMS..." class="w-full rounded-lg border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>
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

        <main class="px-6 lg:px-10 py-8 bg-[#fdf2ee] min-h-[calc(100vh-77px)]">

            {{-- Page header --}}
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold">Menu Management</h1>
                    <p class="mt-1 text-sm text-neutral-600">Manage your restaurant offerings, pricing, and availability.</p>
                </div>

                <button
                    type="button"
                    onclick="openMenuModal('create')"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold shrink-0"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Add New Item
                </button>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search + filters --}}
            <form method="GET" action="{{ route('admin.menu.index') }}" class="mt-6 flex flex-col lg:flex-row items-stretch lg:items-center gap-3 bg-white rounded-2xl border border-neutral-100 shadow-sm p-4">
                <div class="relative flex-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search menu items..."
                           class="w-full rounded-lg border border-neutral-200 pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>

                <select name="category" onchange="this.form.submit()" class="rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30 lg:w-52">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30 lg:w-44">
                    <option value="">All Statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>

                <button type="submit" class="rounded-lg bg-neutral-100 hover:bg-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700">
                    Search
                </button>
                @if ($search || $categoryId || $status)
                    <a href="{{ route('admin.menu.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-semibold text-neutral-500 hover:text-neutral-700">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Table --}}
            <div class="mt-6 bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-neutral-100 text-left text-neutral-500">
                                <th class="px-6 py-4 font-semibold">Product</th>
                                <th class="px-6 py-4 font-semibold">Category</th>
                                <th class="px-6 py-4 font-semibold">Price</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menuItems as $item)
                                <tr class="border-b border-neutral-50 last:border-0 align-middle">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($item->image_url)
                                                <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                            @else
                                                <div class="w-14 h-14 rounded-xl bg-neutral-100 flex items-center justify-center shrink-0 text-neutral-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                                        <circle cx="9" cy="9" r="2"/>
                                                        <path d="m21 15-5-5L5 21"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="min-w-0 {{ $item->status === 'inactive' ? 'opacity-50' : '' }}">
                                                <p class="font-bold truncate">{{ $item->name }}</p>
                                                @if ($item->description)
                                                    <p class="text-neutral-500 truncate">{{ $item->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-orange-50 text-[#9a5138] px-3 py-1 text-xs font-semibold">
                                            {{ $item->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        @if ($item->status === 'active')
                                            <span class="inline-flex items-center rounded-full bg-teal-50 text-teal-700 px-3 py-1 text-xs font-semibold">Active</span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-red-50 text-red-600 px-3 py-1 text-xs font-semibold">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                onclick='openMenuModal("edit", {{ $item->id }}, {{ Illuminate\Support\Js::from([
                                                    "category_id" => $item->category_id,
                                                    "name" => $item->name,
                                                    "description" => $item->description,
                                                    "price" => $item->price,
                                                    "status" => $item->status,
                                                    "image_url" => $item->image_url,
                                                ]) }})'
                                                class="inline-flex items-center justify-center rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-700 w-9 h-9"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"/>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('admin.menu.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this menu item?');">
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
                                    <td colspan="5" class="px-6 py-12 text-center text-neutral-500">
                                        Belum ada menu item. Klik "Add New Item" untuk menambahkan yang pertama.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination footer --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-6 py-4 border-t border-neutral-100">
                    <p class="text-sm text-neutral-500">
                        Showing {{ $menuItems->firstItem() ?? 0 }} to {{ $menuItems->lastItem() ?? 0 }} of {{ $menuItems->total() }} items
                    </p>
                    <div>
                        {{ $menuItems->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

{{-- ===================== ADD / EDIT MENU ITEM MODAL ===================== --}}
<div id="menuModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between">
            <h2 id="menuModalTitle" class="text-lg font-extrabold">Add New Item</h2>
            <button type="button" onclick="closeMenuModal()" class="text-neutral-400 hover:text-neutral-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="menuForm" action="{{ route('admin.menu.store') }}" method="POST" class="mt-5 space-y-4" enctype="multipart/form-data">
            @csrf
            <div id="menuMethodField"></div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Product Name</label>
                <input id="menuNameInput" type="text" name="name" required placeholder="e.g. Grilled Ribeye Steak"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Description</label>
                <input id="menuDescriptionInput" type="text" name="description" placeholder="e.g. 12oz, garlic butter"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Category</label>
                    <select id="menuCategoryInput" name="category_id" required class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Price</label>
                    <input id="menuPriceInput" type="number" name="price" step="0.01" min="0" required placeholder="0.00"
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Status</label>
                <select id="menuStatusInput" name="status" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Photo</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-neutral-600 file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3.5 file:py-2 file:text-sm file:font-semibold file:text-neutral-800">
                <p id="menuCurrentImage" class="mt-1.5 text-xs text-neutral-500"></p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeMenuModal()"
                        class="flex-1 rounded-lg border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] px-4 py-2.5 text-sm font-semibold text-white">
                    Save Item
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const menuIndexUrl = @js(route('admin.menu.index'));

    function openMenuModal(mode, id = null, data = {}) {
        const form = document.getElementById('menuForm');
        const title = document.getElementById('menuModalTitle');
        const methodField = document.getElementById('menuMethodField');
        const currentImage = document.getElementById('menuCurrentImage');

        document.getElementById('menuNameInput').value = data.name ?? '';
        document.getElementById('menuDescriptionInput').value = data.description ?? '';
        document.getElementById('menuCategoryInput').value = data.category_id ?? '';
        document.getElementById('menuPriceInput').value = data.price ?? '';
        document.getElementById('menuStatusInput').value = data.status ?? 'active';

        if (mode === 'edit') {
            title.textContent = 'Edit Menu Item';
            form.action = `${menuIndexUrl}/${id}`;
            methodField.innerHTML = '@method('PUT')';
            currentImage.textContent = data.image_url ? 'Leave empty to keep the current photo.' : '';
        } else {
            title.textContent = 'Add New Item';
            form.action = menuIndexUrl;
            methodField.innerHTML = '';
            currentImage.textContent = '';
        }

        document.getElementById('menuModal').classList.remove('hidden');
    }

    function closeMenuModal() {
        document.getElementById('menuModal').classList.add('hidden');
    }

    @if ($errors->any())
        openMenuModal('create');
    @endif
</script>

</body>
</html>