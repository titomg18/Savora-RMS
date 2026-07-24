<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f4f2] text-neutral-900">

@php
    // $categories dikirim dari CategoryController@index (Eloquent collection).
    $navItems = [
        ['label' => 'Dashboard',   'url' => route('dashboard')],
        ['label' => 'Orders'],
        ['label' => 'Menu'],
        ['label' => 'Categories', 'url' => route('admin.categories.index'), 'active' => true],
        ['label' => 'Tables'],
        ['label' => 'Kitchen'],
        ['label' => 'Payments'],
        ['label' => 'Inventory'],
        ['label' => 'Reports'],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings'],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Header --}}
        <header class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between px-6 lg:px-10 py-6 bg-[#fdf2ee] border-b border-orange-100">
            <div>
                <h1 class="text-3xl font-extrabold">Categories</h1>
                <p class="mt-1 text-sm text-neutral-600">Manage your menu structure and classifications.</p>
            </div>

            <button
                type="button"
                onclick="openCategoryModal('create')"
                class="inline-flex items-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add Category
            </button>
        </header>

        <main class="px-6 lg:px-10 py-8">

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

            {{-- Category cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                @forelse ($categories as $category)
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden flex flex-col">

                        {{-- Cover image + icon badge --}}
                        <div class="relative h-40 bg-neutral-100">
                            @if ($category->image_url)
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/10"></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-neutral-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                                        <circle cx="9" cy="9" r="2"/>
                                        <path d="m21 15-5-5L5 21"/>
                                    </svg>
                                </div>
                            @endif

                            <span
                                class="absolute bottom-4 left-4 w-10 h-10 rounded-xl bg-white shadow flex items-center justify-center
                                    {{ $category->color === 'teal' ? 'text-teal-600' : 'text-[#d9603b]' }}"
                            >
                                @switch($category->icon)
                                    @case('utensils')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 2v7c0 1.1.9 2 2 2h1a2 2 0 0 0 2-2V2"/>
                                            <path d="M6 11v11"/>
                                            <path d="M17 2c-2 0-3 2-3 5s1 5 3 5v10"/>
                                        </svg>
                                        @break
                                    @case('drink')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M5 3h14l-1.6 16.2A2 2 0 0 1 15.4 21H8.6a2 2 0 0 1-2-1.8L5 3Z"/>
                                            <path d="M6 8h12"/>
                                        </svg>
                                        @break
                                    @case('cake')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2v4"/>
                                            <path d="M4 21v-6a4 4 0 0 1 4-4h8a4 4 0 0 1 4 4v6"/>
                                            <path d="M2 21h20"/>
                                            <path d="M4 15c1 1 2 1 3 0s2-1 3 0 2 1 3 0 2-1 3 0 2 1 3 0"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M4 4c4 4 4 12 12 16"/>
                                            <path d="M20 4c-4 4-4 12-12 16"/>
                                        </svg>
                                @endswitch
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="text-lg font-extrabold truncate" title="{{ $category->name }}">{{ $category->name }}</h3>
                            </div>

                            <p class="mt-1.5 flex items-center gap-1.5 text-sm text-neutral-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M8 6h13M8 12h13M8 18h13"/>
                                    <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                                {{ $category->items_count }} Items
                            </p>

                            <div class="mt-4 pt-4 border-t border-neutral-100 flex items-center gap-2">
                                <button
                                    type="button"
                                    onclick="openCategoryModal('edit', {{ $category->id }}, @js($category->name), '{{ $category->icon }}', '{{ $category->color }}', @js($category->image_url))"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-800 px-3 py-2.5 text-sm font-semibold"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 20h9"/>
                                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                    </svg>
                                    Edit
                                </button>

                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                      onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-50 hover:bg-red-100 text-red-600 px-3 py-2.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"/>
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-neutral-100 shadow-sm p-10 text-center text-neutral-500">
                        Belum ada kategori. Klik "Add Category" untuk membuat kategori pertama.
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</div>

{{-- ===================== ADD / EDIT CATEGORY MODAL ===================== --}}
<div id="categoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <h2 id="categoryModalTitle" class="text-lg font-extrabold">Add Category</h2>
            <button type="button" onclick="closeCategoryModal()" class="text-neutral-400 hover:text-neutral-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="categoryForm" action="{{ route('admin.categories.store') }}" method="POST" class="mt-5 space-y-4" enctype="multipart/form-data">
            @csrf
            <div id="categoryMethodField"></div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Category Name</label>
                <input id="categoryNameInput" type="text" name="name" required placeholder="e.g. Main Course"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Icon</label>
                    <select id="categoryIconInput" name="icon" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="utensils">Utensils (Main Course)</option>
                        <option value="drink">Drink (Beverages)</option>
                        <option value="cake">Cake (Desserts)</option>
                        <option value="dumpling">Dumpling (Appetizers)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Badge Color</label>
                    <select id="categoryColorInput" name="color" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="orange">Orange</option>
                        <option value="teal">Teal</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Cover Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-neutral-600 file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3.5 file:py-2 file:text-sm file:font-semibold file:text-neutral-800">
                <p id="categoryCurrentImage" class="mt-1.5 text-xs text-neutral-500"></p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeCategoryModal()"
                        class="flex-1 rounded-lg border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] px-4 py-2.5 text-sm font-semibold text-white">
                    Save Category
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const categoriesIndexUrl = @js(route('admin.categories.index'));

    function openCategoryModal(mode, id = null, name = '', icon = 'utensils', color = 'orange', imageUrl = null) {
        const form = document.getElementById('categoryForm');
        const title = document.getElementById('categoryModalTitle');
        const methodField = document.getElementById('categoryMethodField');
        const currentImage = document.getElementById('categoryCurrentImage');

        document.getElementById('categoryNameInput').value = name;
        document.getElementById('categoryIconInput').value = icon;
        document.getElementById('categoryColorInput').value = color;

        if (mode === 'edit') {
            title.textContent = 'Edit Category';
            form.action = `${categoriesIndexUrl}/${id}`;
            methodField.innerHTML = '@method('PUT')';
            currentImage.textContent = imageUrl ? 'Leave empty to keep the current image.' : '';
        } else {
            title.textContent = 'Add Category';
            form.action = categoriesIndexUrl;
            methodField.innerHTML = '';
            currentImage.textContent = '';
        }

        document.getElementById('categoryModal').classList.remove('hidden');
    }

    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    @if ($errors->any())
        // Kalau validasi gagal (server-side), buka lagi modal Add supaya errornya kelihatan.
        openCategoryModal('create');
    @endif
</script>

</body>
</html>