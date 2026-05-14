@extends('layouts.auth')

@section('title', 'Daftar Merchant Baru')

@section('content')
<div class="auth-card" style="max-width:560px;">
    <!-- Header -->
    <div class="auth-header">
        <div style="font-size:2.5rem; margin-bottom:12px;">
            <i class="fa-solid fa-cart-shopping" style="background: linear-gradient(135deg, hsl(245,82%,58%), hsl(280,80%,55%)); -webkit-background-clip:text; -webkit-text-fill-color:transparent;"></i>
        </div>
        <h1>Bergabung di DigiMart</h1>
        <p>Daftarkan toko Anda dan mulai kelola katalog produk digital</p>
    </div>

    <!-- Flash Message -->
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Register Form -->
    <form action="{{ route('register.process') }}" method="POST" id="formRegister">
        @csrf

        {{-- Row: Nama & Nama Toko --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label" for="name">
                    <i class="fa-solid fa-user" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Nama Lengkap
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    placeholder="Nama Anda"
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="store_name">
                    <i class="fa-solid fa-store" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Nama Toko
                </label>
                <input
                    type="text"
                    id="store_name"
                    name="store_name"
                    class="form-control"
                    placeholder="Nama Toko Anda"
                    value="{{ old('store_name') }}"
                    required
                >
                @error('store_name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="email">
                <i class="fa-solid fa-envelope" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                Email
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                placeholder="email@merchant.com"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Row: Password & Konfirmasi --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fa-solid fa-lock" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Min. 8 karakter"
                    required
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">
                    <i class="fa-solid fa-shield-check" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Konfirmasi Password
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password"
                    required
                >
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="phone">
                <i class="fa-solid fa-phone" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                No. Telepon <span style="color:var(--text-muted); font-weight:400;">(opsional)</span>
            </label>
            <input
                type="tel"
                id="phone"
                name="phone"
                class="form-control"
                placeholder="08xxxxxxxxxx"
                value="{{ old('phone') }}"
                inputmode="numeric"
                pattern="[0-9]*"
                maxlength="15"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
            >
            <span style="font-size:0.8rem; color:var(--text-muted); margin-top:5px; display:block;">
                <i class="fa-solid fa-circle-info"></i> Hanya angka yang diperbolehkan (contoh: 08123456789)
            </span>
            @error('phone')
                <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="address">
                <i class="fa-solid fa-map-pin" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                Alamat Toko <span style="color:var(--text-muted); font-weight:400;">(opsional)</span>
            </label>
            <textarea
                id="address"
                name="address"
                class="form-control"
                placeholder="Alamat lengkap toko..."
                rows="2"
            >{{ old('address') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary" id="btnRegister" style="width:100%; margin-top:8px;">
            <i class="fa-solid fa-user-plus"></i>
            Daftar Sebagai Merchant
        </button>
    </form>

    <div class="auth-footer">
        Sudah punya akun?
        <a href="{{ route('login') }}" id="linkLogin">Login di sini</a>
    </div>
</div>
@endsection
