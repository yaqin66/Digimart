<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * Dashboard Controller - Integrated Dashboard untuk Merchant.
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan data statistik.
     * Menampilkan pesan selamat datang dinamis dari session.
     */
    public function index()
    {
        $merchantId = session('merchant_id');

        $totalCategories = Category::where('merchant_id', $merchantId)->count();
        $activeCategories = Category::where('merchant_id', $merchantId)->where('is_active', true)->count();
        $totalProducts   = Product::where('merchant_id', $merchantId)->count();
        $activeProducts  = Product::where('merchant_id', $merchantId)->where('is_active', true)->count();

        $recentCategories = Category::where('merchant_id', $merchantId)
            ->latest()
            ->take(5)
            ->get();

        $recentProducts = Product::where('merchant_id', $merchantId)
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Data untuk Chart: Kategori dan jumlah produknya
        $chartDataRaw = Category::where('merchant_id', $merchantId)
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        $chartLabels = $chartDataRaw->pluck('name');
        $chartData   = $chartDataRaw->pluck('products_count');

        return view('dashboard.index', compact(
            'totalCategories',
            'activeCategories',
            'totalProducts',
            'activeProducts',
            'recentCategories',
            'recentProducts',
            'chartLabels',
            'chartData'
        ));
    }
}
