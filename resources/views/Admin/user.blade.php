<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f6f4f2] text-neutral-900">

@php
    $navItems = [
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Orders'],
        ['label' => 'Menu'],
        ['label' => 'Categories'],
        ['label' => 'Tables'],
        ['label' => 'Kitchen'],
        ['label' => 'Payments'],
        ['label' => 'Inventory'],
        ['label' => 'Reports'],
        ['label' => 'Users', 'url' => route('admin.users.index'), 'active' => true],
        ['label' => 'Settings'],
    ];

    $roleBadge = fn (string $role) => match ($role) {
        'admin'   => 'bg-orange-100 text-[#c0451f]',
        'owner'   => 'bg-purple-100 text-purple-700',
        'cashier' => 'bg-teal-100 text-teal-700',
        'waiter'  => 'bg-blue-100 text-blue-700',
        'chef'    => 'bg-amber-100 text-amber-700',
        default   => 'bg-neutral-100 text-neutral-600',
    };
@endphp

<div class="min-h-screen flex">

    @include('admin.partials.sidebar', ['navItems' => $navItems])

    <div class="flex-1 min-w-0">

        @include('admin.partials.navbar', [
            'pageTitle' => 'Users',
            'pageSubtitle' => 'Kelola akun admin, owner, cashier, waiter, dan chef restoran kamu.',
        ])

        <main class="px-6 lg:px-10 py-8 space-y-6">

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    <p class="font-semibold mb-1">Periksa kembali data yang kamu masukkan:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">

                {{-- Toolbar: search + add user --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-80">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-neutral-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="11" cy="11" r="7"/>
                                <path d="m21 21-4.3-4.3"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama atau email..."
                            class="w-full rounded-full border border-neutral-200 bg-white pl-10 pr-4 py-2.5 text-sm placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30"
                        >
                    </form>

                    <button
                        type="button"
                        onclick="openModal('modal-create')"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-2.5 text-sm font-semibold"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Tambah User
                    </button>
                </div>

                {{-- Table --}}
                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-neutral-500 border-b border-neutral-100">
                                <th class="pb-3 font-medium">Nama</th>
                                <th class="pb-3 font-medium">Email</th>
                                <th class="pb-3 font-medium">Role</th>
                                <th class="pb-3 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-neutral-50 last:border-0">
                                    <td class="py-3.5 font-semibold">{{ $user->name }}</td>
                                    <td class="py-3.5 text-neutral-600">{{ $user->email }}</td>
                                    <td class="py-3.5">
                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold capitalize {{ $roleBadge($user->role) }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="py-3.5">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                onclick="openModal('modal-edit-{{ $user->id }}')"
                                                class="rounded-lg border border-neutral-200 px-3 py-1.5 text-xs font-semibold text-neutral-700 hover:bg-neutral-50"
                                            >
                                                Edit
                                            </button>

                                            <form
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-neutral-400">
                                        Belum ada user{{ $search ? ' yang cocok dengan pencarian "' . $search . '"' : '' }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="mt-6">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

{{-- ===================== MODAL: TAMBAH USER ===================== --}}
<div id="modal-create" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-create')"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-extrabold">Tambah User Baru</h3>
            <button type="button" onclick="closeModal('modal-create')" class="text-neutral-400 hover:text-neutral-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Nama Lengkap</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Budi Santoso"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="user@restaurant.com"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Role</label>
                <select
                    name="role"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                >
                    <option value="" disabled selected>Pilih role</option>
                    @foreach (\App\Models\User::ROLES as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1.5">Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    required
                    class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                >
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white font-semibold py-2.5 text-sm">
                    Simpan User
                </button>
                <button type="button" onclick="closeModal('modal-create')" class="flex-1 rounded-lg border border-neutral-200 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL: EDIT USER (satu per baris) ===================== --}}
@foreach ($users as $user)
    <div id="modal-edit-{{ $user->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('modal-edit-{{ $user->id }}')"></div>

        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold">Edit User</h3>
                <button type="button" onclick="closeModal('modal-edit-{{ $user->id }}')" class="text-neutral-400 hover:text-neutral-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mt-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Role</label>
                    <select
                        name="role"
                        required
                        class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                    >
                        @foreach (\App\Models\User::ROLES as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">
                        Password <span class="text-neutral-400 font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1.5">Konfirmasi Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="••••••••"
                        class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/40 focus:border-[#d9603b]"
                    >
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white font-semibold py-2.5 text-sm">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="closeModal('modal-edit-{{ $user->id }}')" class="flex-1 rounded-lg border border-neutral-200 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script>
    function openModal(id) {
        document.getElementById(id)?.classList.remove('hidden');
    }
    function closeModal(id) {
        document.getElementById(id)?.classList.add('hidden');
    }
    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('[id^="modal-"]').forEach((el) => el.classList.add('hidden'));
        }
    });

    @if ($errors->any())
        // Kalau validasi gagal, buka lagi modal create supaya errornya kelihatan.
        // (Untuk modal edit, karena ada banyak, silakan tambahkan logic serupa
        // kalau kamu ingin modal edit yang gagal validasi ikut auto-terbuka.)
        openModal('modal-create');
    @endif
</script>

</body>
</html>