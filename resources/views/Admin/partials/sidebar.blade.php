{{--
    Sidebar partial.
    $navItems bisa dikirim dari view pemanggil; kalau tidak dikirim, pakai default di bawah.
    Untuk menandai menu aktif secara otomatis, kamu bisa ganti 'active' => true
    dengan request()->routeIs('admin.dashboard') dsb.
--}}
@php
    $navItems = $navItems ?? [
        ['label' => 'Dashboard',   'url' => route('dashboard'), 'active' => true],
        ['label' => 'Orders'],
        ['label' => 'Menu'],
        ['label' => 'Categories'],
        ['label' => 'Tables'],
        ['label' => 'Kitchen'],
        ['label' => 'Payments'],
        ['label' => 'Inventory'],
        ['label' => 'Reports'],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings'],
    ];
@endphp

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
                href="{{ $item['url'] ?? '#' }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ $item['active'] ?? false
                        ? 'bg-white text-[#d9603b] shadow-sm'
                        : 'text-neutral-600 hover:bg-white/60' }}"
            >
                <span class="w-5 h-5 flex items-center justify-center">
                    {{-- simple dot as generic icon placeholder; swap with heroicons per item if you like --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
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
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <path d="M16 17l5-5-5-5"/>
                <path d="M21 12H9"/>
            </svg>
            Logout
        </button>
    </form>
</aside>