<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | Savora RMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#f4f1f5] text-neutral-900">

@php
    // $tab (string) & $setting (App\Models\Setting) dikirim dari SettingController@edit.

    $tabs = [
        'restaurant' => ['label' => 'Restaurant Info', 'icon' => 'store'],
        'hours' => ['label' => 'Business Hours', 'icon' => 'clock'],
        'tax' => ['label' => 'Tax & Pricing', 'icon' => 'receipt'],
        'printer' => ['label' => 'Printer Settings', 'icon' => 'printer'],
        'security' => ['label' => 'Security', 'icon' => 'shield'],
    ];

    $weekdayLabels = [
        'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday',
        'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday',
    ];
@endphp

<div class="min-h-screen flex">

    {{-- ===================== SIDEBAR ===================== --}}
    @include('Landing.partials.sidebar')

    {{-- ===================== MAIN ===================== --}}
    <div class="flex-1 min-w-0">

        {{-- Topbar --}}
        <header class="flex items-center gap-4 px-6 lg:px-10 py-5 bg-[#fdf2ee] border-b border-orange-100">
            <div class="relative w-full max-w-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search settings..." class="w-full rounded-lg border border-orange-100 bg-[#fdf1ea] pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
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

        <main class="px-6 lg:px-10 py-8 bg-[#f4f1f5] min-h-[calc(100vh-77px)]">
            <h1 class="text-3xl font-extrabold mb-6">Settings</h1>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-6 items-start">

                {{-- ===================== SUB-NAV ===================== --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-2">
                    @foreach ($tabs as $key => $meta)
                        <a
                            href="{{ route('admin.settings.edit', ['tab' => $key]) }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold mb-1 last:mb-0
                                {{ $tab === $key ? 'bg-orange-50 text-[#c0472e]' : 'text-neutral-700 hover:bg-neutral-50' }}"
                        >
                            @switch($meta['icon'])
                                @case('store')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M3 9V7l2-4h14l2 4v2"/>
                                        <path d="M3 9a2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0 2 2 0 0 0 4 0"/>
                                        <path d="M5 9v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9"/>
                                        <path d="M9 21v-6h6v6"/>
                                    </svg>
                                    @break
                                @case('clock')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="9"/>
                                        <path d="M12 7v5l3 3"/>
                                    </svg>
                                    @break
                                @case('receipt')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M6 2h9l3 3v17l-3-2-3 2-3-2-3 2V2Z"/>
                                        <path d="M9 8h6M9 12h6M9 16h4"/>
                                    </svg>
                                    @break
                                @case('printer')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M6 9V2h12v7"/>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                        <path d="M6 14h12v8H6z"/>
                                    </svg>
                                    @break
                                @default
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M12 2 4 5v6c0 5 3.5 8.5 8 11 4.5-2.5 8-6 8-11V5l-8-3Z"/>
                                    </svg>
                            @endswitch
                            {{ $meta['label'] }}
                        </a>
                    @endforeach
                </div>

                {{-- ===================== PANEL ===================== --}}
                <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-8">

                    @if ($tab === 'restaurant')
                        <h2 class="text-xl font-extrabold">Restaurant Information</h2>
                        <p class="mt-1 text-sm text-neutral-500">Update your primary business details and public profile.</p>
                        <div class="mt-5 border-t border-neutral-100"></div>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-[220px_1fr] gap-8">
                            {{-- Logo --}}
                            <div class="flex flex-col items-center text-center">
                                <div class="w-40 h-40 rounded-full bg-neutral-50 border border-neutral-100 flex items-center justify-center overflow-hidden">
                                    @if ($setting->logo_url)
                                        <img src="{{ $setting->logo_url }}" alt="Logo" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-neutral-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M12 2 4 5v6c0 5 3.5 8.5 8 11 4.5-2.5 8-6 8-11V5l-8-3Z"/>
                                        </svg>
                                    @endif
                                </div>

                                <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                    @csrf
                                    <label class="cursor-pointer inline-flex items-center gap-2 rounded-lg border border-orange-200 text-[#c0472e] px-4 py-2.5 text-sm font-semibold hover:bg-orange-50">
                                        Upload New Logo
                                        <input type="file" name="logo" accept="image/*" class="hidden" onchange="this.form.submit()">
                                    </label>
                                </form>
                                <p class="mt-2.5 text-xs text-neutral-400">JPG, GIF or PNG. Max size of 800K</p>
                            </div>

                            {{-- Info form --}}
                            <form action="{{ route('admin.settings.restaurant') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Restaurant Name</label>
                                    <input type="text" name="restaurant_name" value="{{ old('restaurant_name', $setting->restaurant_name) }}" required
                                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Legal Business Name</label>
                                    <input type="text" name="legal_name" value="{{ old('legal_name', $setting->legal_name) }}"
                                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email', $setting->email) }}"
                                               class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}"
                                               class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Street Address</label>
                                    <input type="text" name="address" value="{{ old('address', $setting->address) }}"
                                           class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">City</label>
                                        <input type="text" name="city" value="{{ old('city', $setting->city) }}"
                                               class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">State/Province</label>
                                        <input type="text" name="state" value="{{ old('state', $setting->state) }}"
                                               class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-700 mb-1.5">ZIP/Postal</label>
                                        <input type="text" name="zip" value="{{ old('zip', $setting->zip) }}"
                                               class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-neutral-100 flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.settings.edit', ['tab' => 'restaurant']) }}"
                                       class="rounded-lg border border-neutral-200 px-5 py-2.5 text-sm font-semibold text-neutral-700 hover:bg-neutral-50">
                                        Discard Changes
                                    </a>
                                    <button type="submit"
                                            class="rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-5 py-2.5 text-sm font-semibold">
                                        Save Information
                                    </button>
                                </div>
                            </form>
                        </div>

                    @elseif ($tab === 'hours')
                        <h2 class="text-xl font-extrabold">Business Hours</h2>
                        <p class="mt-1 text-sm text-neutral-500">Set when each day your restaurant is open for orders.</p>
                        <div class="mt-5 border-t border-neutral-100"></div>

                        <form action="{{ route('admin.settings.hours') }}" method="POST" class="mt-6 space-y-3">
                            @csrf
                            @foreach ($weekdayLabels as $day => $label)
                                @php $row = $setting->business_hours[$day] ?? ['open' => '09:00', 'close' => '22:00', 'closed' => false]; @endphp
                                <div class="flex flex-wrap items-center gap-4 rounded-xl border border-neutral-100 px-4 py-3.5">
                                    <span class="w-28 font-semibold text-sm">{{ $label }}</span>

                                    <label class="flex items-center gap-2 text-sm text-neutral-600">
                                        <input type="checkbox" name="hours[{{ $day }}][closed]" value="1" {{ $row['closed'] ? 'checked' : '' }}
                                               class="rounded border-neutral-300 text-[#dd6b4a] focus:ring-[#d9603b]/30">
                                        Closed
                                    </label>

                                    <div class="flex items-center gap-2 ml-auto">
                                        <input type="time" name="hours[{{ $day }}][open]" value="{{ $row['open'] }}"
                                               class="rounded-lg border border-neutral-200 px-3 py-2 text-sm">
                                        <span class="text-neutral-400">to</span>
                                        <input type="time" name="hours[{{ $day }}][close]" value="{{ $row['close'] }}"
                                               class="rounded-lg border border-neutral-200 px-3 py-2 text-sm">
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-5 py-2.5 text-sm font-semibold">
                                    Save Business Hours
                                </button>
                            </div>
                        </form>

                    @elseif ($tab === 'tax')
                        <h2 class="text-xl font-extrabold">Tax & Pricing</h2>
                        <p class="mt-1 text-sm text-neutral-500">Configure the tax rate applied to every order.</p>
                        <div class="mt-5 border-t border-neutral-100"></div>

                        <form action="{{ route('admin.settings.tax') }}" method="POST" class="mt-6 max-w-md space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" step="0.01" min="0" max="100" value="{{ old('tax_rate', $setting->tax_rate) }}"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                <p class="mt-1.5 text-xs text-neutral-400">Dipakai sebagai default perhitungan pajak di halaman Orders.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Currency</label>
                                <select name="currency" class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                    @foreach (['USD', 'EUR', 'GBP', 'IDR', 'AUD'] as $currency)
                                        <option value="{{ $currency }}" @selected($setting->currency === $currency)>{{ $currency }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-5 py-2.5 text-sm font-semibold">
                                    Save Tax Settings
                                </button>
                            </div>
                        </form>

                    @elseif ($tab === 'printer')
                        <h2 class="text-xl font-extrabold">Printer Settings</h2>
                        <p class="mt-1 text-sm text-neutral-500">Choose which printer handles receipts and kitchen tickets.</p>
                        <div class="mt-5 border-t border-neutral-100"></div>

                        <form action="{{ route('admin.settings.printer') }}" method="POST" class="mt-6 max-w-md space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Receipt Printer</label>
                                <input type="text" name="receipt_printer" value="{{ old('receipt_printer', $setting->receipt_printer) }}" placeholder="e.g. Epson TM-T88VI"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Kitchen Printer</label>
                                <input type="text" name="kitchen_printer" value="{{ old('kitchen_printer', $setting->kitchen_printer) }}" placeholder="e.g. Star TSP143"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                            </div>
                            <label class="flex items-center gap-2 text-sm text-neutral-700">
                                <input type="checkbox" name="auto_print_kitchen" value="1" {{ $setting->auto_print_kitchen ? 'checked' : '' }}
                                       class="rounded border-neutral-300 text-[#dd6b4a] focus:ring-[#d9603b]/30">
                                Automatically print kitchen ticket when an order is submitted
                            </label>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-5 py-2.5 text-sm font-semibold">
                                    Save Printer Settings
                                </button>
                            </div>
                        </form>

                    @elseif ($tab === 'security')
                        <h2 class="text-xl font-extrabold">Security</h2>
                        <p class="mt-1 text-sm text-neutral-500">Update the password for your own account ({{ auth()->user()->email }}).</p>
                        <div class="mt-5 border-t border-neutral-100"></div>

                        <form action="{{ route('admin.settings.password') }}" method="POST" class="mt-6 max-w-md space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Current Password</label>
                                <input type="password" name="current_password"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                                @error('current_password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">New Password</label>
                                <input type="password" name="password"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-neutral-700 mb-1.5">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                       class="w-full rounded-lg border border-neutral-200 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#d9603b]/30">
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit" class="rounded-lg bg-[#dd6b4a] hover:bg-[#c85a3b] text-white px-5 py-2.5 text-sm font-semibold">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>