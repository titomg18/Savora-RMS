{{--
    Sidebar partial — SATU-SATUNYA sumber daftar menu untuk semua halaman Admin.

    Sengaja TIDAK menerima $navItems dari luar lagi (biar gak ada lagi daftar menu
    yang beda-beda/ketinggalan di tiap halaman). Kalau nambah menu baru, cukup edit
    array $navItems di bawah ini SEKALI SAJA, otomatis kepakai di semua halaman yang
    meng-include partial ini.

    Status aktif dideteksi otomatis lewat request()->routeIs(), bukan di-hardcode,
    jadi gak akan ada lagi menu yang "nyangkut" aktif di halaman yang salah.
--}}
@php
    use Illuminate\Support\Facades\Route;

    // Role mana saja yang boleh LIHAT tiap menu di sidebar. Ini cuma soal tampilan —
    // proteksi sesungguhnya tetap di middleware masing-masing route/controller;
    // daftar ini cuma disamakan manual biar gak ada menu yang kelihatan tapi ternyata 403.
    $navItems = [
        ['label' => 'Dashboard',  'route' => 'dashboard',              'active_pattern' => ['dashboard', 'admin.dashboard'], 'roles' => ['admin', 'owner', 'cashier', 'waiter', 'chef']],
        ['label' => 'Orders',     'route' => 'admin.orders.index',     'active_pattern' => 'admin.orders.*',     'roles' => ['admin', 'owner', 'waiter', 'cashier']],
        ['label' => 'Menu',       'route' => 'admin.menu.index',       'active_pattern' => 'admin.menu.*',       'roles' => ['admin', 'owner']],
        ['label' => 'Categories', 'route' => 'admin.categories.index', 'active_pattern' => 'admin.categories.*', 'roles' => ['admin', 'owner']],
        ['label' => 'Tables',     'route' => 'admin.tables.index',     'active_pattern' => 'admin.tables.*',     'roles' => ['admin', 'owner', 'waiter']],
        ['label' => 'Kitchen',    'route' => 'admin.kitchen.index',    'active_pattern' => 'admin.kitchen.*',    'roles' => ['admin', 'owner', 'chef']],
        ['label' => 'Payments',   'route' => 'admin.payments.index',   'active_pattern' => 'admin.payments.*',   'roles' => ['admin', 'owner', 'cashier']],
        ['label' => 'Inventory',  'route' => 'admin.inventory.index',  'active_pattern' => 'admin.inventory.*',  'roles' => ['admin', 'owner']],
        ['label' => 'Reports',    'route' => 'admin.reports.index',    'active_pattern' => 'admin.reports.*',    'roles' => ['admin', 'owner']],
        ['label' => 'Users',      'route' => 'admin.users.index',      'active_pattern' => 'admin.users.*',      'roles' => ['admin']],
        ['label' => 'Settings',   'route' => 'admin.settings.edit',    'active_pattern' => 'admin.settings.*',   'roles' => ['admin', 'owner']],
    ];
@endphp

<aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-[#fdf2ee] border-r border-orange-100 px-5 py-6">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-2">
        <span class="text-xl">🍴</span>
        <div class="leading-tight">
            <p class="font-extrabold text-[#d9603b] text-lg">Savora RMS</p>
            <p class="text-xs text-neutral-500">Management Suite</p>
        </div>
    </a>

    <nav class="mt-8 flex-1 space-y-1">
        @foreach ($navItems as $item)
            @continue(! in_array(auth()->user()?->role, $item['roles'] ?? [], true))
            @php
                $url = Route::has($item['route']) ? route($item['route']) : '#';
                $isActive = request()->routeIs($item['active_pattern']);
            @endphp
            <a
                href="{{ $url }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                    {{ $isActive
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