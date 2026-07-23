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

{{--
    Data di bawah ini contoh (dummy) supaya halaman bisa langsung dilihat.
    Di project asli, ganti dengan data dari Controller, misalnya:
    return view('admin.categories', compact('categories'));

    Struktur tiap item $categories:
    'name'   => nama kategori
    'items'  => jumlah item di kategori itu
    'image'  => url gambar cover
    'icon'   => 'utensils' | 'drink' | 'cake' | 'dumpling' (dipakai buat pilih svg icon di bawah)
    'color'  => 'orange' | 'teal' (warna badge icon)
--}}
@php
    $categories = $categories ?? [
        [
            'id'    => 1,
            'name'  => 'Main Course',
            'items' => 42,
            'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=400&auto=format&fit=crop',
            'icon'  => 'utensils',
            'color' => 'orange',
        ],
        [
            'id'    => 2,
            'name'  => 'Beverages',
            'items' => 28,
            'image' => 'https://images.unsplash.com/photo-1543253687-c931c8e01820?q=80&w=400&auto=format&fit=crop',
            'icon'  => 'drink',
            'color' => 'teal',
        ],
        [
            'id'    => 3,
            'name'  => 'Desserts',
            'items' => 15,
            'image' => 'https://images.unsplash.com/photo-1587314168485-3236d6710814?q=80&w=400&auto=format&fit=crop',
            'icon'  => 'cake',
            'color' => 'teal',
        ],
        [
            'id'    => 4,
            'name'  => 'Appetizers',
            'items' => 24,
            'image' => 'https://images.unsplash.com/photo-1541014741259-de529411b96a?q=80&w=400&auto=format&fit=crop',
            'icon'  => 'dumpling',
            'color' => 'orange',
        ],
    ];

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
                onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                class="inline-flex items-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold shrink-0"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add Category
            </button>
        </header>

        <main class="px-6 lg:px-10 py-8">

            {{-- Category cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                @foreach ($categories as $category)
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden flex flex-col">

                        {{-- Cover image + icon badge --}}
                        <div class="relative h-40">
                            <img
                                src="{{ $category['image'] }}"
                                alt="{{ $category['name'] }}"
                                class="w-full h-full object-cover"
                            >
                            <div class="absolute inset-0 bg-black/10"></div>

                            <span
                                class="absolute bottom-4 left-4 w-10 h-10 rounded-xl bg-white shadow flex items-center justify-center
                                    {{ $category['color'] === 'teal' ? 'text-teal-600' : 'text-[#d9603b]' }}"
                            >
                                @switch($category['icon'])
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
                                <h3 class="text-lg font-extrabold truncate">{{ $category['name'] }}</h3>
                                <button type="button" class="text-neutral-400 hover:text-neutral-700 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <circle cx="12" cy="5" r="1.6"/>
                                        <circle cx="12" cy="12" r="1.6"/>
                                        <circle cx="12" cy="19" r="1.6"/>
                                    </svg>
                                </button>
                            </div>

                            <p class="mt-1.5 flex items-center gap-1.5 text-sm text-neutral-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M8 6h13M8 12h13M8 18h13"/>
                                    <path d="M3 6h.01M3 12h.01M3 18h.01"/>
                                </svg>
                                {{ $category['items'] }} Items
                            </p>

                            <div class="mt-4 pt-4 border-t border-neutral-100 flex items-center gap-2">
                                <form action="{{ \Illuminate\Support\Facades\Route::has('admin.categories.edit') ? route('admin.categories.edit', $category['id']) : '#' }}" method="GET" class="flex-1">
                                    <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-800 px-3 py-2.5 text-sm font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                        </svg>
                                        Edit
                                    </button>
                                </form>

                                <form action="{{ \Illuminate\Support\Facades\Route::has('admin.categories.destroy') ? route('admin.categories.destroy', $category['id']) : '#' }}" method="POST"
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
                @endforeach
            </div>

            @if (count($categories) === 0)
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-10 text-center text-neutral-500">
                    Belum ada kategori. Klik "Add Category" untuk membuat kategori pertama.
                </div>
            @endif
        </main>
    </div>
</div>

{{-- ===================== ADD CATEGORY MODAL ===================== --}}
<div id="addCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-extrabold">Add Category</h2>
            <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="text-neutral-400 hover:text-neutral-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ \Illuminate\Support\Facades\Route::has('admin.categories.store') ? route('admin.categories.store') : '#' }}" method="POST" class="mt-5 space-y-4" enctype="multipart/form-data">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Category Name</label>
                <input type="text" name="name" required placeholder="e.g. Main Course"
                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>

            <div>
                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Cover Image</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-neutral-600 file:mr-3 file:rounded-lg file:border-0 file:bg-neutral-100 file:px-3.5 file:py-2 file:text-sm file:font-semibold file:text-neutral-800">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
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

</body>
</html>