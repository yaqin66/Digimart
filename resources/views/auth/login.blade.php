@extends('layouts.auth')
@section('title', 'Login Merchant')

@section('content')
<div class="auth-card">

    {{-- Brand --}}
    <div class="auth-brand">
        <div class="auth-brand-logo">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <h1>Masuk ke DigiMart</h1>
        <p>Login sebagai Merchant resmi untuk mengelola katalog Anda</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif

    <form action="{{ route('login.process') }}" method="POST" id="formLogin">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">
                <i class="fa-solid fa-envelope" style="color:#6366f1; margin-right:6px;"></i>Email
            </label>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="email@merchant.com" value="{{ old('email') }}" required autofocus>
            @error('email') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">
                <i class="fa-solid fa-lock" style="color:#6366f1; margin-right:6px;"></i>Password
            </label>
            <input type="password" id="password" name="password" class="form-control"
                   placeholder="Masukkan password Anda" required>
            @error('password') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary" id="btnLogin" style="width:100%; padding:13px; font-size:0.95rem; margin-top:4px;">
            <i class="fa-solid fa-right-to-bracket"></i>
            Masuk ke Dashboard
        </button>
    </form>

    <div class="auth-footer">
        Belum punya akun? <a href="{{ route('register') }}" id="linkRegister">Daftar Sekarang</a>
    </div>
</div>
@endsection
