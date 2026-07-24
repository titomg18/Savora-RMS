<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('category');
        $status = $request->query('status');

        $menuItems = MenuItem::query()
            ->with('category')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('Admin.menu', compact('menuItems', 'categories', 'search', 'categoryId', 'status'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menu', 'public');
        }

        MenuItem::create($validated);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu item baru berhasil ditambahkan.');
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }

            $validated['image'] = $request->file('image')->store('menu', 'public');
        }

        $menuItem->update($validated);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu item berhasil diperbarui.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu item berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(MenuItem::STATUSES)],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}