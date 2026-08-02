<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,owner')
        ->name('admin.dashboard');

    Route::get('/dashboard', function () {
        return redirect()->to(auth()->user()->getDashboardRoute());
    })->name('dashboard');

    // ==== Admin-only management (CRUD) — hanya admin & owner ====
    // Catatan: 'role:admin,owner' asumsinya middleware role kamu menerima
    // beberapa role sekaligus (dipisah koma) sebagai parameter variadic.
    // Kalau middleware kamu cuma menerima satu role, ganti jadi 'role:admin' saja.

    // ==== Users (kelola akun staff) — KHUSUS role 'admin', Owner sengaja TIDAK dikasih akses ke sini ====
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // ==== Tables & Orders — admin, owner, DAN waiter ====
    // Sama seperti Kitchen/Payments: akses role diatur lewat middleware() masing-masing controller,
    // bukan di sini, karena beda action (mis. store/destroy meja) butuh role berbeda dari index/update.
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tables', TableController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('orders', OrderController::class)->only(['index', 'store']);
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin,owner')->group(function () {
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('menu', MenuController::class)
            ->parameters(['menu' => 'menuItem'])
            ->only(['index', 'store', 'update', 'destroy']);
        Route::resource('inventory', InventoryController::class)
            ->parameters(['inventory' => 'inventoryItem'])
            ->only(['index', 'store', 'update', 'destroy']);

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/items', [ReportController::class, 'items'])->name('reports.items');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::post('settings/restaurant', [SettingController::class, 'updateRestaurant'])->name('settings.restaurant');
        Route::post('settings/logo', [SettingController::class, 'updateLogo'])->name('settings.logo');
        Route::post('settings/hours', [SettingController::class, 'updateHours'])->name('settings.hours');
        Route::post('settings/tax', [SettingController::class, 'updateTax'])->name('settings.tax');
        Route::post('settings/printer', [SettingController::class, 'updatePrinters'])->name('settings.printer');
        Route::post('settings/password', [SettingController::class, 'updatePassword'])->name('settings.password');
    });

    // ==== Kitchen Display (KDS) — admin, owner, DAN chef ====
    // Dipisah dari group di atas karena group itu di-lock 'role:admin,owner' saja;
    // akses role di sini diatur lewat KitchenController::middleware() sendiri.
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
        Route::post('kitchen/orders/{order}/advance', [KitchenController::class, 'advance'])->name('kitchen.advance');
        Route::post('kitchen/items/{orderItem}/toggle', [KitchenController::class, 'toggleItem'])->name('kitchen.toggle_item');
    });

    // ==== Payments — admin, owner, DAN cashier ====
    // Sama seperti Kitchen: akses role diatur lewat PaymentController::middleware() sendiri.
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::post('payments/{order}/apply-promo', [PaymentController::class, 'applyPromo'])->name('payments.apply_promo');
        Route::post('payments/{order}/complete', [PaymentController::class, 'complete'])->name('payments.complete');
    });
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});