<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistoryCache
{
    /**
     * Matiin cache browser buat semua halaman, biar tombol Back gak nampilin
     * halaman lama (termasuk halaman yang butuh login) setelah user logout.
     *
     * Tanpa ini: browser boleh nyimpen halaman di "back/forward cache" (bfcache)
     * dan nampilin lagi versi lama itu pas tombol Back diklik — tanpa request baru
     * ke server — jadi kelihatan "masih login" padahal session-nya udah dihapus.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}