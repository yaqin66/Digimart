<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware (Filter) untuk memproteksi halaman dashboard.
 * Memastikan hanya Merchant yang sudah login dapat mengakses.
 */
class AuthMerchant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah merchant sudah login via session
        if (!session()->has('merchant_id')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
