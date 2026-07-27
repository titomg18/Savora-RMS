<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryDelivery;
use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InventoryController extends Controller implements HasMiddleware
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
        $category = $request->query('category');
        $status = $request->query('status');

        $items = InventoryItem::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('name')
            ->get()
            // Status stok dihitung di PHP (accessor), jadi filter status juga di collection,
            // bukan lewat query builder.
            ->when($status, fn ($collection) => $collection->where('stock_status', $status))
            ->values();

        // Pagination manual karena filter status dilakukan di collection, bukan di query.
        $perPage = 5;
        $page = (int) $request->query('page', 1);
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $categories = InventoryItem::query()->distinct()->orderBy('category')->pluck('category');

        $stats = [
            'total' => InventoryItem::query()->count(),
            'low_stock_alerts' => InventoryItem::query()
                ->whereColumn('current_stock', '<', 'minimum_stock')
                ->count(),
            'pending_deliveries' => InventoryDelivery::query()->where('status', 'pending')->count(),
        ];

        return view('Admin.inventory', [
            'items' => $paginatedItems,
            'categories' => $categories,
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        InventoryItem::create($validated);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Item inventory baru berhasil ditambahkan.');
    }

    public function update(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $validated = $this->validated($request);

        $inventoryItem->update($validated);

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy(InventoryItem $inventoryItem): RedirectResponse
    {
        $inventoryItem->delete();

        return redirect()
            ->route('admin.inventory.index')
            ->with('success', 'Item inventory berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:20'],
            'current_stock' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
        ]);
    }
}