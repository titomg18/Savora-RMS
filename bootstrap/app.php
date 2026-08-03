<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\PreventBackHistoryCache;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan middleware alias di sini
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Matiin cache browser di semua halaman, biar tombol Back gak nampilin
        // halaman lama setelah logout (lihat komentar di PreventBackHistoryCache).
        $middleware->web(append: [
            PreventBackHistoryCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();