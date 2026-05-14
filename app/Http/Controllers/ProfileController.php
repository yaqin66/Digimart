<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk manajemen profil Merchant yang sedang login.
 */
class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil merchant.
     */
    public function show()
    {
        $merchant = Merchant::findOrFail(session('merchant_id'));
        return view('profile.edit', compact('merchant'));
    }

    /**
     * Perbarui data profil merchant.
     */
    public function update(Request $request)
    {
        $merchantId = session('merchant_id');
        $merchant   = Merchant::findOrFail($merchantId);

        $request->validate([
            'name'       => 'required|string|max:100',
            'store_name' => 'required|string|max:150',
            'phone'      => 'nullable|regex:/^[0-9]+$/|max:15',
            'address'    => 'nullable|string|max:500',
            'email'      => 'required|email|unique:merchants,email,' . $merchantId,
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'store_name.required' => 'Nama toko wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email sudah digunakan akun lain.',
            'phone.regex'         => 'Nomor telepon hanya boleh berisi angka.',
            'phone.max'           => 'Nomor telepon maksimal 15 digit.',
        ]);

        $merchant->update([
            'name'       => $request->name,
            'store_name' => $request->store_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'address'    => $request->address,
        ]);

        // Update session agar navbar langsung terupdate
        session([
            'merchant_name' => $merchant->name,
            'store_name'    => $merchant->store_name,
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Proses ubah password merchant.
     */
    public function updatePassword(Request $request)
    {
        $merchantId = session('merchant_id');
        $merchant   = Merchant::findOrFail($merchantId);

        $request->validate([
            'current_password'          => 'required',
            'new_password'              => 'required|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ], [
            'current_password.required'  => 'Password lama wajib diisi.',
            'new_password.required'      => 'Password baru wajib diisi.',
            'new_password.min'           => 'Password baru minimal 8 karakter.',
            'new_password.confirmed'     => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Verifikasi password lama
        if (!Hash::check($request->current_password, $merchant->password)) {
            return back()
                ->withInput()
                ->with('password_error', 'Password lama yang Anda masukkan salah.');
        }

        // Pastikan password baru tidak sama dengan yang lama
        if (Hash::check($request->new_password, $merchant->password)) {
            return back()
                ->with('password_error', 'Password baru tidak boleh sama dengan password lama.');
        }

        $merchant->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Password berhasil diubah. Silakan login ulang jika diperlukan.');
    }
}
