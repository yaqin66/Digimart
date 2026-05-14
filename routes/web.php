<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthMerchant;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Struktur URL yang bersih untuk platform Smart-Catalog UMKM.
| Dilengkapi dengan middleware proteksi rute dashboard & manajemen.
*/

// Halaman utama diarahkan ke dashboard (akan difilter oleh middleware jika belum login)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ────────────────────── AUTHENTICATION ROUTES ──────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ──────────────── Protected Merchant Routes (Dashboard & CRUD) ───────
Route::middleware([AuthMerchant::class])->group(function () {
    // Integrated Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Merchant
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // CRUD Kategori dengan struktur URL yang bersih
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [CategoryController::class, 'toggleStatus'])->name('toggle');
    });

    // CRUD Produk dengan fitur unggah foto multimedia
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle', [ProductController::class, 'toggleStatus'])->name('toggle');
    });
});

// Rute untuk menyajikan gambar bawaan jika produk tidak memiliki foto
Route::get('/images/no-image.png', function () {
    // Kita buat response gambar kosong sederhana atau placeholder SVG jika diakses
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
                <rect width="200" height="200" fill="#f3f4f6"/>
                <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="14" fill="#9ca3af">No Photo</text>
            </svg>';
    return response($svg, 200)->header('Content-Type', 'image/svg+xml');
});
