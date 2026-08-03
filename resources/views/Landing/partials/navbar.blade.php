{{--
    Navbar / header partial.
    Kirim $pageTitle dan $pageSubtitle dari view pemanggil kalau mau judul beda-beda per halaman, misalnya:
    @include('Landing.partials.navbar', ['pageTitle' => 'Orders', 'pageSubtitle' => 'Kelola semua pesanan di sini.'])
--}}
@php
    $pageTitle    = $pageTitle    ?? 'Overview';
    $pageSubtitle = $pageSubtitle ?? "Welcome back, " . (auth()->user()->name ?? 'Admin') . ". Here's what's happening today.";
@endphp

<header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between px-6 lg:px-10 py-6 bg-[#fdf2ee] border-b border-orange-100">
    <div>
        <h1 class="text-3xl font-extrabold">{{ $pageTitle }}</h1>
        <p class="mt-1 text-sm text-neutral-600">
            {{ $pageSubtitle }}
        </p>
    </div>

    <div class="flex items-center gap-3">
        <div class="relative hidden md:block">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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