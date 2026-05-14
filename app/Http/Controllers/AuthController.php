<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk Authentication: Login & Register Merchant.
 */
class AuthController extends Controller
{
    // ─────────────────────── SHOW FORMS ────────────────────────

    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        // Redirect jika sudah login
        if (session()->has('merchant_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Tampilkan halaman register.
     */
    public function showRegister()
    {
        if (session()->has('merchant_id')) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    // ─────────────────────── PROCESS ───────────────────────────

    /**
     * Proses login merchant.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $merchant = Merchant::where('email', $request->email)->first();

        if (!$merchant || !Hash::check($request->password, $merchant->password)) {
            return back()
                ->withInput()
                ->with('error', 'Email atau password salah. Silakan coba lagi.');
        }

        // Simpan session merchant
        session([
            'merchant_id'   => $merchant->id,
            'merchant_name' => $merchant->name,
            'store_name'    => $merchant->store_name,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang kembali, ' . $merchant->name . '!');
    }

    /**
     * Proses register merchant baru.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:merchants,email',
            'password'   => 'required|min:8|confirmed',
            'store_name' => 'required|string|max:150',
            'phone'      => 'nullable|regex:/^[0-9]+$/|max:15',
            'address'    => 'nullable|string|max:500',
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'store_name.required' => 'Nama toko wajib diisi.',
            'phone.regex'         => 'Nomor telepon hanya boleh berisi angka.',
            'phone.max'           => 'Nomor telepon maksimal 15 digit.',
        ]);

        // Buat merchant baru dengan password di-hash (bcrypt)
        $merchant = Merchant::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password), // Enkripsi password
            'store_name' => $request->store_name,
            'phone'      => $request->phone,
            'address'    => $request->address,
        ]);

        // Auto login setelah register
        session([
            'merchant_id'   => $merchant->id,
            'merchant_name' => $merchant->name,
            'store_name'    => $merchant->store_name,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil! Selamat datang di Smart-Catalog, ' . $merchant->name . '!');
    }

    /**
     * Proses logout merchant.
     */
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
