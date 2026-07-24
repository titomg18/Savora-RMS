<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TableController extends Controller implements HasMiddleware
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
        $area = $request->query('area', 'main');

        if (! in_array($area, DiningTable::AREAS, true)) {
            $area = 'main';
        }

        $tables = DiningTable::query()
            ->where('area', $area)
            ->orderBy('sort_order')
            ->orderBy('number')
            ->get();

        return view('Admin.tables', compact('tables', 'area'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        DiningTable::create($validated);

        return redirect()
            ->route('admin.tables.index', ['area' => $validated['area']])
            ->with('success', 'Meja baru berhasil ditambahkan.');
    }

    public function update(Request $request, DiningTable $table): RedirectResponse
    {
        $validated = $this->validated($request, $table);

        $table->update($validated);

        return redirect()
            ->route('admin.tables.index', ['area' => $validated['area']])
            ->with('success', 'Data meja berhasil diperbarui.');
    }

    public function destroy(DiningTable $table): RedirectResponse
    {
        $area = $table->area;
        $table->delete();

        return redirect()
            ->route('admin.tables.index', ['area' => $area])
            ->with('success', 'Meja berhasil dihapus.');
    }

    private function validated(Request $request, ?DiningTable $table = null): array
    {
        return $request->validate([
            'number' => [
                'required', 'integer', 'min:1',
                Rule::unique('dining_tables')
                    ->where(fn ($query) => $query->where('area', $request->input('area')))
                    ->ignore($table?->id),
            ],
            'seats' => ['required', 'integer', 'min:1', 'max:20'],
            'area' => ['required', Rule::in(DiningTable::AREAS)],
            'status' => ['required', Rule::in(DiningTable::STATUSES)],
            'label' => ['nullable', 'string', 'max:50'],
            'subtitle' => ['nullable', 'string', 'max:100'],
        ]);
    }
}