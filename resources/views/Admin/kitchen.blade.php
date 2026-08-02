<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Display | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900 min-h-screen">

@php
    // $orders (dengan relasi table, user, items.menuItem), $station, $stationCounts
    // dikirim dari KitchenController@index.
    $statusMeta = [
        'waiting' => ['border' => 'border-orange-300', 'bar' => 'bg-orange-400', 'header' => 'bg-[#fdf1ea]', 'badge' => 'bg-amber-50 text-amber-700 border-amber-200', 'timer' => 'text-orange-500'],
        'cooking' => ['border' => 'border-teal-300',   'bar' => 'bg-teal-600',   'header' => 'bg-teal-50/40',  'badge' => 'bg-teal-50 text-teal-700 border-teal-200',   'timer' => 'text-teal-600'],
        'ready'   => ['border' => 'border-green-300',  'bar' => 'bg-green-600',  'header' => 'bg-green-50/40', 'badge' => 'bg-green-50 text-green-700 border-green-200','timer' => 'text-green-600'],
    ];

    $stationTabs = [
        'all'   => 'All',
        'grill' => 'Grill',
        'prep'  => 'Prep',
    ];
@endphp

<div
    x-data="kitchenDisplay({
        advanceUrlBase: @js(route('admin.kitchen.index')),
        toggleUrlBase: @js(url('/admin/kitchen/items')),
        csrfToken: @js(csrf_token()),
    })"
>

    {{-- ===================== TOP BAR (tanpa sidebar, layar dapur full-width) ===================== --}}
    <header class="flex items-center gap-4 px-6 lg:px-10 py-4 bg-[#fdf2ee] border-b border-orange-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#d9603b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 3l18 18M21 3 3 21"/>
            </svg>
            <span class="font-extrabold text-[#d9603b] text-lg">Savora RMS</span>
        </a>

        <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-white border border-orange-100 px-3 py-1.5 text-xs font-semibold text-neutral-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 13a10 10 0 0 1 14 0"/>
                <path d="M8.5 16.5a5 5 0 0 1 7 0"/>
                <circle cx="12" cy="20" r="1"/>
            </svg>
            KDS - Main Kitchen
        </span>

        <nav class="ml-4 hidden md:flex items-center gap-5 text-sm font-semibold">
            @foreach ($stationTabs as $key => $label)
                <a
                    href="{{ route('admin.kitchen.index', $key === 'all' ? [] : ['station' => $key]) }}"
                    class="{{ $station === $key ? 'text-[#d9603b]' : 'text-neutral-500 hover:text-neutral-800' }}"
                >
                    {{ $label }} ({{ $stationCounts[$key] ?? 0 }})
                </a>
            @endforeach
        </nav>

        <div class="ml-auto flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <button type="button" class="inline-flex items-center gap-1.5 rounded-full border border-orange-200 text-[#d9603b] px-4 py-2 text-sm font-semibold hover:bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M3 9h18M9 21V9"/>
                </svg>
                Station View
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-neutral-200 text-neutral-500 hover:text-red-600 hover:border-red-200 hover:bg-red-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <path d="M16 17l5-5-5-5"/>
                        <path d="M21 12H9"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    {{-- ===================== ORDER TICKETS ===================== --}}
    <main class="px-6 lg:px-10 py-8">
        <div class="flex flex-wrap gap-6 items-start">
            @forelse ($orders as $order)
                @php $meta = $statusMeta[$order->kitchen_status] ?? $statusMeta['waiting']; @endphp
                <div
                    x-data="{ id: {{ $order->id }}, createdAt: @js($order->created_at->toIso8601String()), kitchenStatus: @js($order->kitchen_status) }"
                    x-init="registerTicket($el, id, createdAt, kitchenStatus)"
                    class="w-[290px] shrink-0 bg-white rounded-2xl border-2 {{ $meta['border'] }} overflow-hidden flex flex-col shadow-sm"
                >
                    <div class="h-1.5 {{ $meta['bar'] }}"></div>

                    {{-- Header --}}
                    <div class="{{ $meta['header'] }} px-5 pt-4 pb-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-mono text-neutral-500">#{{ \Illuminate\Support\Str::after($order->order_number, 'ORD-') }}</span>
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-xs font-semibold {{ $meta['badge'] }}">
                                    {{ ucfirst($order->kitchen_status) }}
                                </span>
                            </div>
                            <div class="text-right {{ $meta['timer'] }}">
                                <div class="flex items-center gap-1 font-extrabold text-base leading-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="13" r="8"/>
                                        <path d="M12 9v4l2.5 2.5"/>
                                        <path d="M9 2h6"/>
                                    </svg>
                                    <span data-elapsed>{{ (int) $order->created_at->diffInMinutes(now()) }}m</span>
                                </div>
                                <p class="mt-1 text-xs text-neutral-500 font-normal">{{ $order->created_at->format('g:i A') }}</p>
                            </div>
                        </div>

                        <p class="mt-2 text-xl font-extrabold">
                            {{ $order->table ? 'Table ' . $order->table->formatted_number : 'Takeout #' . $order->id }}
                        </p>
                        <p class="text-sm text-neutral-500">
                            {{ $order->user ? 'Server: ' . $order->user->name : 'Walk-in' }}
                        </p>
                    </div>

                    {{-- Items --}}
                    <div class="flex-1 divide-y divide-neutral-100">
                        @foreach ($order->items as $item)
                            <label
                                class="flex items-start gap-3 px-5 py-3.5 cursor-pointer {{ $item->is_prepared ? 'bg-orange-50/60' : '' }}"
                                @click.prevent="toggleItem({{ $item->id }})"
                            >
                                <span
                                    class="mt-0.5 w-5 h-5 rounded-md border flex items-center justify-center shrink-0 {{ $item->is_prepared ? 'bg-teal-600 border-teal-600' : 'border-neutral-300' }}"
                                    x-bind:class="isItemPrepared({{ $item->id }}, {{ $item->is_prepared ? 'true' : 'false' }}) ? 'bg-teal-600 border-teal-600' : 'border-neutral-300'"
                                >
                                    <svg x-show="isItemPrepared({{ $item->id }}, {{ $item->is_prepared ? 'true' : 'false' }})" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                </span>

                                <div class="min-w-0" x-bind:class="isItemPrepared({{ $item->id }}, {{ $item->is_prepared ? 'true' : 'false' }}) ? 'opacity-50' : ''">
                                    <p class="font-bold" x-bind:class="isItemPrepared({{ $item->id }}, {{ $item->is_prepared ? 'true' : 'false' }}) ? 'line-through' : ''">
                                        {{ $item->quantity }}x {{ $item->name }}
                                    </p>

                                    @if ($item->note)
                                        <p class="mt-0.5 text-sm font-semibold {{ $item->is_allergy ? 'text-red-600 flex items-center gap-1' : 'text-[#c04a30]' }}">
                                            @if ($item->is_allergy)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z"/>
                                                    <path d="M12 9v4M12 17h.01"/>
                                                </svg>
                                            @endif
                                            {{ $item->note }}
                                        </p>
                                    @endif

                                    @if ($item->side)
                                        <p class="mt-0.5 text-sm text-neutral-500">{{ $item->side }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    {{-- Action button --}}
                    <div class="p-4">
                        @if ($order->kitchen_status === 'waiting')
                            <button type="button" @click="advance({{ $order->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-3.5 text-sm font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2c-1 3-4 4-4 8a4 4 0 0 0 8 0c0-1-.5-2-1-2 0 1.5-1 2-1 2 .5-2-1-3-1-4-.5 1-2 2-2 4a2 2 0 0 1-2-2c0-3 2-4 3-6Z"/>
                                    <path d="M6 20h12"/>
                                </svg>
                                Start Cooking
                            </button>
                        @elseif ($order->kitchen_status === 'cooking')
                            <button type="button" @click="advance({{ $order->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white px-4 py-3.5 text-sm font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 17h18"/>
                                    <path d="M5 17a7 7 0 0 1 14 0"/>
                                    <path d="M9 17v-2a3 3 0 0 1 6 0v2"/>
                                </svg>
                                Ready to Serve
                            </button>
                        @else
                            <button type="button" @click="advance({{ $order->id }})"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-neutral-800 hover:bg-neutral-900 text-white px-4 py-3.5 text-sm font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Mark as Served
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="w-full text-center text-neutral-400 py-24">
                    Tidak ada order aktif di dapur saat ini. 🎉
                </div>
            @endforelse
        </div>
    </main>
</div>

<script>
    function kitchenDisplay({ advanceUrlBase, toggleUrlBase, csrfToken }) {
        return {
            csrfToken,
            advanceUrlBase,
            toggleUrlBase,
            tickets: {}, // { [orderId]: { createdAt, el } }
            preparedOverrides: {}, // { [itemId]: bool } — override optimistik setelah diklik

            init() {
                setInterval(() => {
                    Object.keys(this.tickets).forEach((id) => this.updateTimer(id));
                }, 30000);
            },

            registerTicket(el, id, createdAt) {
                this.tickets[id] = { createdAt: new Date(createdAt), el };
                this.updateTimer(id);
            },

            updateTimer(id) {
                const ticket = this.tickets[id];
                if (!ticket) return;
                const minutes = Math.max(0, Math.round((Date.now() - ticket.createdAt.getTime()) / 60000));
                const label = ticket.el.querySelector('[data-elapsed]');
                if (label) label.textContent = minutes + 'm';
            },

            isItemPrepared(itemId, initial) {
                return itemId in this.preparedOverrides ? this.preparedOverrides[itemId] : initial;
            },

            async toggleItem(itemId) {
                this.preparedOverrides[itemId] = !this.isItemPrepared(itemId, false);

                try {
                    await fetch(`${this.toggleUrlBase}/${itemId}/toggle`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    });
                } catch (e) {
                    // biarin state optimistik tetap jalan meski request gagal; refresh manual kalau perlu
                }
            },

            async advance(orderId) {
                try {
                    const response = await fetch(`${this.advanceUrlBase}/orders/${orderId}/advance`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrfToken, 'Accept': 'application/json' },
                    });
                    if (response.ok) {
                        window.location.reload();
                    }
                } catch (e) {
                    alert('Gagal update status order. Coba lagi.');
                }
            },
        };
    }
</script>

</body>
</html>