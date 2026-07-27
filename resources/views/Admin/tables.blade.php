<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Management | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

@php
    // $tables (koleksi DiningTable untuk area terpilih) & $area dikirim dari TableController@index.
    $navItems = [
        ['label' => 'Dashboard',   'url' => route('dashboard')],
        ['label' => 'Orders', 'url' => route('admin.orders.index')],
        ['label' => 'Menu', 'url' => route('admin.menu.index')],
        ['label' => 'Categories', 'url' => route('admin.categories.index')],
        ['label' => 'Tables', 'url' => route('admin.tables.index'), 'active' => true],
        ['label' => 'Kitchen', 'url' => route('admin.kitchen.index')],
        ['label' => 'Payments', 'url' => route('admin.payments.index')],
        ['label' => 'Inventory', 'url' => route('admin.inventory.index')],
        ['label' => 'Reports'],
        ['label' => 'Users', 'url' => route('admin.users.index')],
        ['label' => 'Settings'],
    ];

    $statusStyles = [
        'available' => ['dot' => 'bg-teal-400',  'card' => 'bg-white border-neutral-200'],
        'occupied'  => ['dot' => 'bg-red-500',   'card' => 'bg-[#fdf1ee] border-orange-100'],
        'reserved'  => ['dot' => 'bg-orange-400','card' => 'bg-[#fdf1ee] border-orange-100'],
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar', ['navItems' => $navItems])

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <h2 class="text-lg font-bold shrink-0">Table Management</h2>

            <div class="relative ml-auto w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search..." class="w-full rounded-lg border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
            </div>
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
                    <h1 class="text-3xl font-extrabold">Tables</h1>
                    <p class="mt-1 text-sm text-neutral-600">Manage dining area seating and current statuses.</p>
                </div>

                <div class="flex items-center gap-4 bg-white rounded-lg border border-orange-100 px-4 py-2.5 shrink-0">
                    <span class="flex items-center gap-1.5 text-sm text-neutral-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-teal-400"></span> Available
                    </span>
                    <span class="flex items-center gap-1.5 text-sm text-neutral-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Occupied
                    </span>
                    <span class="flex items-center gap-1.5 text-sm text-neutral-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span> Reserved
                    </span>
                </div>
            </div>

            {{-- Flash messages --}}
            @if (session('success'))
                <div class="mt-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Table cards --}}
            <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
                @forelse ($tables as $table)
                    @php $style = $statusStyles[$table->status]; @endphp
                    <button
                        type="button"
                        onclick='openTableModal({{ $table->id }}, {{ Illuminate\Support\Js::from([
                            "number" => $table->number,
                            "seats" => $table->seats,
                            "area" => $table->area,
                            "status" => $table->status,
                            "label" => $table->label,
                            "subtitle" => $table->subtitle,
                        ]) }})'
                        class="relative text-left rounded-2xl border {{ $style['card'] }} p-5 hover:shadow-md transition-shadow"
                    >
                        <span class="absolute top-4 right-4 w-2.5 h-2.5 rounded-full {{ $style['dot'] }}"></span>

                        @if ($table->label)
                            <p class="text-xs font-bold text-red-600 tracking-wide">{{ $table->label }}</p>
                        @endif

                        <p class="text-5xl font-extrabold leading-none {{ $table->label ? 'mt-1' : '' }}">{{ $table->formatted_number }}</p>

                        <p class="mt-3 flex items-center gap-1.5 text-sm text-neutral-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 18v-2a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2"/>
                                <path d="M5 14V9a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v5"/>
                                <path d="M3 18v2M21 18v2"/>
                            </svg>
                            {{ $table->seats }} Seats
                        </p>

                        @if ($table->subtitle)
                            <p class="mt-1 text-sm font-bold text-red-600">{{ $table->subtitle }}</p>
                        @endif
                    </button>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-neutral-200 p-10 text-center text-neutral-500">
                        Belum ada meja di area ini. Klik "Add Table" untuk menambahkan.
                    </div>
                @endforelse

                {{-- Add table card --}}
                <button
                    type="button"
                    onclick="openTableModal(null, { area: '{{ $area }}' })"
                    class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-dashed border-orange-200 text-[#d9603b] p-5 hover:bg-white transition-colors min-h-[164px]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    <span class="text-sm font-semibold">Add Table</span>
                </button>
            </div>

            {{-- Area switcher --}}
            <div class="mt-10 pt-6 border-t border-orange-100 text-center text-sm text-neutral-600">
                Showing {{ $area === 'main' ? 'main dining area' : ucfirst($area) . ' area' }}. Switch to
                @foreach (\App\Models\DiningTable::AREAS as $areaOption)
                    @continue($areaOption === $area)
                    <a href="{{ route('admin.tables.index', ['area' => $areaOption]) }}" class="font-bold text-[#d9603b] hover:underline">{{ ucfirst($areaOption) }}</a>@if (! $loop->last) or @endif
                @endforeach
                views.
            </div>
        </main>
    </div>
</div>

{{-- ===================== ADD / EDIT TABLE MODAL ===================== --}}
<div id="tableModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <div class="flex items-center justify-between">
            <h2 id="tableModalTitle" class="text-lg font-extrabold">Add Table</h2>
            <button type="button" onclick="closeTableModal()" class="text-neutral-400 hover:text-neutral-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="tableForm" action="{{ route('admin.tables.store') }}" method="POST" class="mt-5 space-y-4">
            @csrf
            <div id="tableMethodField"></div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Table Number</label>
                    <input id="tableNumberInput" type="number" name="number" min="1" required
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Seats</label>
                    <input id="tableSeatsInput" type="number" name="seats" min="1" max="20" required
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Area</label>
                    <select id="tableAreaInput" name="area" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="main">Main</option>
                        <option value="patio">Patio</option>
                        <option value="bar">Bar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Status</label>
                    <select id="tableStatusInput" name="status" onchange="toggleTableExtraFields()" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                        <option value="available">Available</option>
                        <option value="occupied">Occupied</option>
                        <option value="reserved">Reserved</option>
                    </select>
                </div>
            </div>

            <div id="tableExtraFields" class="space-y-4 hidden">
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Label (durasi / jam)</label>
                    <input id="tableLabelInput" type="text" name="label" placeholder="e.g. 1h 15m or 19:30"
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Subtitle (order / party)</label>
                    <input id="tableSubtitleInput" type="text" name="subtitle" placeholder="e.g. Order #4092 or Smith Party"
                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" id="tableDeleteButton" onclick="submitTableDelete()"
                        class="hidden rounded-lg border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50">
                    Delete
                </button>
                <button type="button" onclick="closeTableModal()"
                        class="flex-1 rounded-lg border border-neutral-200 px-4 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    Cancel
                </button>
                <button type="submit"
                        class="flex-1 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] px-4 py-2.5 text-sm font-semibold text-white">
                    Save Table
                </button>
            </div>
        </form>

        <form id="tableDeleteForm" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
    const tablesIndexUrl = @js(route('admin.tables.index'));

    function toggleTableExtraFields() {
        const status = document.getElementById('tableStatusInput').value;
        document.getElementById('tableExtraFields').classList.toggle('hidden', status === 'available');
    }

    function openTableModal(id, data = {}) {
        const form = document.getElementById('tableForm');
        const title = document.getElementById('tableModalTitle');
        const methodField = document.getElementById('tableMethodField');
        const deleteButton = document.getElementById('tableDeleteButton');

        document.getElementById('tableNumberInput').value = data.number ?? '';
        document.getElementById('tableSeatsInput').value = data.seats ?? 4;
        document.getElementById('tableAreaInput').value = data.area ?? 'main';
        document.getElementById('tableStatusInput').value = data.status ?? 'available';
        document.getElementById('tableLabelInput').value = data.label ?? '';
        document.getElementById('tableSubtitleInput').value = data.subtitle ?? '';
        toggleTableExtraFields();

        if (id) {
            title.textContent = `Edit Table ${data.number ?? ''}`;
            form.action = `${tablesIndexUrl}/${id}`;
            methodField.innerHTML = '@method('PUT')';
            deleteButton.classList.remove('hidden');
            document.getElementById('tableDeleteForm').action = `${tablesIndexUrl}/${id}`;
        } else {
            title.textContent = 'Add Table';
            form.action = tablesIndexUrl;
            methodField.innerHTML = '';
            deleteButton.classList.add('hidden');
        }

        document.getElementById('tableModal').classList.remove('hidden');
    }

    function closeTableModal() {
        document.getElementById('tableModal').classList.add('hidden');
    }

    function submitTableDelete() {
        if (confirm('Delete this table?')) {
            document.getElementById('tableDeleteForm').submit();
        }
    }

    @if ($errors->any())
        openTableModal(null, {{ Illuminate\Support\Js::from(old()) }});
    @endif
</script>

</body>
</html>