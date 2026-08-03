<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth',
            new Middleware('role:admin,owner'),
        ];
    }

    public function index(): View
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('Landing.categories', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['required', Rule::in(Category::ICONS)],
            'color' => ['required', Rule::in(Category::COLORS)],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);
    }
}