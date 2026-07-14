<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

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
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});