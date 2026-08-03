<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function edit(Request $request): View
    {
        $tab = $request->query('tab', 'restaurant');
        $setting = Setting::current();

        return view('Landing.settings', compact('tab', 'setting'));
    }

    public function updateRestaurant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'string', 'max:20'],
        ]);

        Setting::current()->update($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'restaurant'])
            ->with('success', 'Informasi restoran berhasil disimpan.');
    }

    public function updateLogo(Request $request): RedirectResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'max:800'], // max 800KB, sesuai teks di UI
        ]);

        $setting = Setting::current();

        if ($setting->logo) {
            Storage::disk('public')->delete($setting->logo);
        }

        $setting->update(['logo' => $request->file('logo')->store('settings', 'public')]);

        return redirect()->route('admin.settings.edit', ['tab' => 'restaurant'])
            ->with('success', 'Logo berhasil diperbarui.');
    }

    public function updateHours(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hours' => ['required', 'array'],
            'hours.*.open' => ['nullable', 'string'],
            'hours.*.close' => ['nullable', 'string'],
            'hours.*.closed' => ['nullable'],
        ]);

        $hours = [];
        foreach (Setting::WEEKDAYS as $day) {
            $row = $validated['hours'][$day] ?? [];
            $hours[$day] = [
                'open' => $row['open'] ?? '09:00',
                'close' => $row['close'] ?? '22:00',
                'closed' => ! empty($row['closed']),
            ];
        }

        Setting::current()->update(['business_hours' => $hours]);

        return redirect()->route('admin.settings.edit', ['tab' => 'hours'])
            ->with('success', 'Jam operasional berhasil disimpan.');
    }

    public function updateTax(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        Setting::current()->update($validated);

        return redirect()->route('admin.settings.edit', ['tab' => 'tax'])
            ->with('success', 'Pengaturan pajak & harga berhasil disimpan.');
    }

    public function updatePrinters(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'receipt_printer' => ['nullable', 'string', 'max:255'],
            'kitchen_printer' => ['nullable', 'string', 'max:255'],
            'auto_print_kitchen' => ['nullable'],
        ]);

        Setting::current()->update([
            'receipt_printer' => $validated['receipt_printer'] ?? null,
            'kitchen_printer' => $validated['kitchen_printer'] ?? null,
            'auto_print_kitchen' => $request->boolean('auto_print_kitchen'),
        ]);

        return redirect()->route('admin.settings.edit', ['tab' => 'printer'])
            ->with('success', 'Pengaturan printer berhasil disimpan.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = Auth::user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.'])->with('tab', 'security');
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('admin.settings.edit', ['tab' => 'security'])
            ->with('success', 'Password berhasil diperbarui.');
    }
}