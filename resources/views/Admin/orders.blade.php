<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#fdf2ee] text-neutral-900">

@php
    // $categories, $menuItems, $tables dikirim dari OrderController@index.
    $taxRate = (float) \App\Models\Setting::current()->tax_rate;
@endphp

<div
    x-data="orderBuilder({
        menuItems: {{ Illuminate\Support\Js::from($menuItems->map(fn ($m) => [
            'id' => $m->id,
            'name' => $m->name,
            'description' => $m->description,
            'price' => (float) $m->price,
            'image' => $m->image_url,
            'category' => $m->category?->name,
        ])) }},
        submitUrl: @js(route('admin.orders.store')),
        csrfToken: @js(csrf_token()),
        taxRate: {{ $taxRate }},
    })"
    class="min-h-screen flex"
>

    {{-- ===================== SIDEBAR ===================== --}}
    @include('admin.partials.sidebar')

    {{-- ===================== BUILDER (search/filter/menu grid) ===================== --}}
    <div class="flex-1 min-w-0 flex flex-col">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <div class="relative w-full max-w-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Global Search..." class="w-full rounded-full border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
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

        {{-- Table / guests / menu search --}}
        <div class="px-6 lg:px-10 py-6 bg-[#fdf2ee] border-b border-orange-100 flex flex-wrap items-end gap-6">
            <div>
                <label class="block text-sm text-neutral-600 mb-1.5">Table / Type</label>
                <select x-model="tableId" class="rounded-lg border border-neutral-200 bg-white px-3.5 py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30 min-w-[190px]">
                    <option value="">Takeaway / No Table</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}">Table {{ $table->formatted_number }} ({{ ucfirst($table->area) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-neutral-600 mb-1.5">Guests</label>
                <div class="flex items-center gap-3 rounded-lg border border-neutral-200 bg-white px-2 py-1.5">
                    <button type="button" @click="guests = Math.max(1, guests - 1)" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-neutral-100 text-neutral-600">−</button>
                    <span class="w-6 text-center font-bold" x-text="guests"></span>
                    <button type="button" @click="guests++" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-neutral-100 text-neutral-600">+</button>
                </div>
            </div>

            <div class="flex-1 min-w-[220px]">
                <label class="block text-sm text-neutral-600 mb-1.5 opacity-0 select-none">Search</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Search menu..." class="w-full rounded-lg border border-neutral-200 bg-white pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                </div>
            </div>
        </div>

        {{-- Category tabs --}}
        <div class="px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100 flex flex-wrap gap-2">
            <button
                type="button"
                @click="activeCategory = 'All Items'"
                :class="activeCategory === 'All Items' ? 'bg-[#dd6b4a] text-white' : 'bg-white text-neutral-600 hover:bg-orange-50'"
                class="rounded-full px-4 py-2 text-sm font-semibold transition-colors"
            >
                All Items
            </button>
            @foreach ($categories as $category)
                <button
                    type="button"
                    @click="activeCategory = @js($category->name)"
                    :class="activeCategory === @js($category->name) ? 'bg-[#dd6b4a] text-white' : 'bg-white text-neutral-600 hover:bg-orange-50'"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition-colors"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- Menu grid --}}
        <main class="flex-1 px-6 lg:px-10 py-8 overflow-y-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="item in filteredItems" :key="item.id">
                    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="relative h-32 bg-neutral-100">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" x-show="item.image" x-cloak>
                            <div x-show="!item.image" class="w-full h-full flex items-center justify-center text-neutral-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M12 2a7 7 0 0 0-7 7c0 3 2 5 3 7l4 6 4-6c1-2 3-4 3-7a7 7 0 0 0-7-7Z"/>
                                </svg>
                            </div>
                            <span class="absolute top-3 right-3 rounded-lg bg-white px-2.5 py-1 text-sm font-bold text-[#d9603b] shadow" x-text="'$' + item.price.toFixed(0)"></span>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <h3 class="font-extrabold" x-text="item.name"></h3>
                            <p class="mt-1 text-sm text-neutral-500 line-clamp-2 flex-1" x-text="item.description"></p>
                            <button
                                type="button"
                                @click="addItem(item)"
                                class="mt-3 inline-flex items-center justify-center gap-1.5 rounded-lg bg-orange-50 hover:bg-orange-100 text-[#d9603b] px-3 py-2 text-sm font-semibold"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14"/>
                                </svg>
                                Add
                            </button>
                        </div>
                    </div>
                </template>

                <p x-show="filteredItems.length === 0" x-cloak class="col-span-full text-center text-neutral-400 py-16">
                    No menu items found.
                </p>
            </div>
        </main>
    </div>

    {{-- ===================== CURRENT ORDER PANEL ===================== --}}
    <aside class="hidden xl:flex xl:flex-col w-[420px] shrink-0 bg-white border-l border-neutral-100">
        <div class="px-6 py-6 border-b border-neutral-100">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-extrabold">Current Order</h2>
                <button type="button" @click="clearCart()" class="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 6h18"/>
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6M14 11v6"/>
                    </svg>
                </button>
            </div>
            <p class="mt-1 text-sm font-mono text-neutral-500" x-text="orderNumberLabel"></p>
        </div>

        <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <template x-for="(line, index) in cart" :key="line.uid">
                <div class="rounded-xl border border-neutral-100 bg-[#fdf9f7] p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="font-bold truncate" x-text="line.name"></p>
                            <p class="text-sm text-neutral-500" x-text="'$' + line.price.toFixed(2)"></p>
                        </div>
                        <p class="font-extrabold text-[#d9603b] shrink-0" x-text="'$' + (line.price * line.quantity).toFixed(2)"></p>
                    </div>

                    <div class="mt-2 flex items-center gap-2">
                        <template x-if="line.note">
                            <span class="inline-flex items-center rounded-full bg-orange-100 text-[#9a5138] px-2.5 py-1 text-xs font-semibold" x-text="line.note"></span>
                        </template>
                        <button type="button" @click="editNote(index)" class="text-neutral-400 hover:text-neutral-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                            </svg>
                        </button>
                    </div>

                    <div class="mt-3 flex items-center justify-between">
                        <div class="flex items-center gap-3 rounded-lg border border-neutral-200 bg-white px-2 py-1.5">
                            <button type="button" @click="decrement(index)" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-neutral-100 text-neutral-600">−</button>
                            <span class="w-5 text-center font-bold" x-text="line.quantity"></span>
                            <button type="button" @click="increment(index)" class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-neutral-100 text-neutral-600">+</button>
                        </div>
                        <button type="button" @click="removeLine(index)" class="text-neutral-400 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M18 6 6 18M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>

            <p x-show="cart.length === 0" x-cloak class="text-center text-neutral-400 py-16">
                No items yet. Tap "Add" on a menu item to start this order.
            </p>
        </div>

        <div class="px-6 py-5 border-t border-neutral-100 bg-[#fdf2ee]">
            <div class="space-y-1.5 text-sm">
                <div class="flex justify-between text-neutral-600">
                    <span>Subtotal</span>
                    <span x-text="'$' + subtotal.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-neutral-600">
                    <span>Tax ({{ rtrim(rtrim(number_format($taxRate, 2), '0'), '.') }}%)</span>
                    <span x-text="'$' + tax.toFixed(2)"></span>
                </div>
                <div class="flex justify-between text-lg font-extrabold pt-1.5">
                    <span>Total</span>
                    <span class="text-[#d9603b]" x-text="'$' + total.toFixed(2)"></span>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <button type="button" @click="submitOrder('held')" :disabled="cart.length === 0 || submitting"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-4 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50 disabled:opacity-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M10 9v6M14 9v6"/>
                    </svg>
                    Hold
                </button>
                <button type="button" @click="window.print()"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-neutral-200 bg-white px-4 py-3 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9V2h12v7"/>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                        <path d="M6 14h12v8H6z"/>
                    </svg>
                    Print
                </button>
            </div>

            <button type="button" @click="submitOrder('submitted')" :disabled="cart.length === 0 || submitting"
                    class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-4 py-3.5 text-sm font-bold disabled:opacity-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 12 21 3l-7 18-2.5-7.5L3 12Z"/>
                </svg>
                <span x-text="submitting ? 'Submitting...' : 'Submit Order'"></span>
            </button>
        </div>
    </aside>
</div>

<script>
    function orderBuilder({ menuItems, submitUrl, csrfToken, taxRate }) {
        return {
            menuItems,
            submitUrl,
            csrfToken,
            taxRate: taxRate / 100, // mis. 8.5 -> 0.085
            tableId: '',
            guests: 2,
            search: '',
            activeCategory: 'All Items',
            cart: [],
            submitting: false,
            orderNumberLabel: 'New Order',

            get filteredItems() {
                return this.menuItems.filter((item) => {
                    const matchesCategory = this.activeCategory === 'All Items' || item.category === this.activeCategory;
                    const matchesSearch = item.name.toLowerCase().includes(this.search.toLowerCase());
                    return matchesCategory && matchesSearch;
                });
            },

            get subtotal() {
                return this.cart.reduce((sum, line) => sum + line.price * line.quantity, 0);
            },

            get tax() {
                return Math.round(this.subtotal * this.taxRate * 100) / 100;
            },

            get total() {
                return this.subtotal + this.tax;
            },

            addItem(item) {
                const existing = this.cart.find((line) => line.menu_item_id === item.id && !line.note);
                if (existing) {
                    existing.quantity++;
                    return;
                }
                this.cart.push({
                    uid: `${item.id}-${Date.now()}`,
                    menu_item_id: item.id,
                    name: item.name,
                    price: item.price,
                    quantity: 1,
                    note: '',
                });
            },

            increment(index) {
                this.cart[index].quantity++;
            },

            decrement(index) {
                if (this.cart[index].quantity > 1) {
                    this.cart[index].quantity--;
                } else {
                    this.removeLine(index);
                }
            },

            removeLine(index) {
                this.cart.splice(index, 1);
            },

            editNote(index) {
                const note = window.prompt('Add a note for this item (e.g. "No Onions")', this.cart[index].note || '');
                if (note !== null) {
                    this.cart[index].note = note.trim();
                }
            },

            clearCart() {
                if (this.cart.length === 0 || confirm('Clear all items from this order?')) {
                    this.cart = [];
                }
            },

            async submitOrder(status) {
                if (this.cart.length === 0 || this.submitting) return;
                this.submitting = true;

                try {
                    const response = await fetch(this.submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            dining_table_id: this.tableId || null,
                            guests: this.guests,
                            status,
                            items: this.cart.map((line) => ({
                                menu_item_id: line.menu_item_id,
                                quantity: line.quantity,
                                note: line.note || null,
                            })),
                        }),
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        alert(data.message || 'Gagal menyimpan order. Cek kembali data yang diisi.');
                        return;
                    }

                    this.orderNumberLabel = data.order_number;
                    alert(data.message);
                    this.cart = [];
                } catch (e) {
                    alert('Terjadi kesalahan jaringan. Coba lagi.');
                } finally {
                    this.submitting = false;
                }
            },
        };
    }
</script>

</body>
</html>