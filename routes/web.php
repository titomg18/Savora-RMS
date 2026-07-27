<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\KitchenController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\InventoryController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->middleware('role:owner')->name('owner.dashboard');

    Route::get('/cashier/dashboard', function () {
        return view('cashier.dashboard');
    })->middleware('role:cashier')->name('cashier.dashboard');

    Route::get('/waiter/dashboard', function () {
        return view('waiter.dashboard');
    })->middleware('role:waiter')->name('waiter.dashboard');

    Route::get('/chef/dashboard', function () {
        return view('chef.dashboard');
    })->middleware('role:chef')->name('chef.dashboard');

    Route::get('/dashboard', function () {
        return redirect()->to(auth()->user()->getDashboardRoute());
    })->name('dashboard');

    // ==== Admin-only management (CRUD) — hanya admin & owner ====
    // Catatan: 'role:admin,owner' asumsinya middleware role kamu menerima
    // beberapa role sekaligus (dipisah koma) sebagai parameter variadic.
    // Kalau middleware kamu cuma menerima satu role, ganti jadi 'role:admin' saja.
    Route::prefix('admin')->name('admin.')->middleware('role:admin,owner')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('menu', MenuController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('tables', TableController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('orders', OrderController::class)->only(['index', 'store']);
        Route::resource('inventory', InventoryController::class)->only(['index', 'store', 'update', 'destroy']);
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