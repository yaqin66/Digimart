@extends('layouts.app')
@section('title', 'Profil Saya')
@section('header_title', 'Profil Saya')

@section('content')
<div style="max-width:820px;">

    {{-- Tab Navigation --}}
    <div style="display:flex; gap:4px; margin-bottom:24px; background:var(--surface); border:1px solid var(--border); border-radius:var(--r-lg); padding:5px; width:fit-content;">
        <button onclick="switchTab('profile')" id="tabBtnProfile"
            style="padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; background: linear-gradient(135deg,#6366f1,#8b5cf6); color:white; box-shadow:0 4px 12px rgba(99,102,241,0.3);"
            class="tab-btn-active">
            <i class="fa-solid fa-user-pen" style="margin-right:6px;"></i>Edit Profil
        </button>
        <button onclick="switchTab('password')" id="tabBtnPassword"
            style="padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s; background:transparent; color:var(--text-secondary);">
            <i class="fa-solid fa-lock" style="margin-right:6px;"></i>Ubah Password
        </button>
    </div>

    {{-- ════════ TAB 1: Edit Profil ════════ --}}
    <div id="tabProfile">
        <div class="premium-card">
            <div style="margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:18px;">
                {{-- Avatar --}}
                <div style="width:64px; height:64px; border-radius:18px; background:linear-gradient(135deg,#6366f1,#8b5cf6); display:flex; align-items:center; justify-content:center; font-size:1.6rem; font-weight:800; color:white; box-shadow:0 8px 20px rgba(99,102,241,0.3); flex-shrink:0;">
                    {{ strtoupper(substr($merchant->name, 0, 1)) }}
                </div>
                <div>
                    <h2 style="font-size:1.1rem; font-weight:800; margin-bottom:2px;">{{ $merchant->name }}</h2>
                    <p style="font-size:0.85rem; color:var(--text-muted);">{{ $merchant->email }}</p>
                    <p style="font-size:0.82rem; color:#6366f1; font-weight:600; margin-top:2px;">
                        <i class="fa-solid fa-store" style="margin-right:4px;"></i>{{ $merchant->store_name }}
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" id="formUpdateProfile">
                @csrf
                @method('PUT')

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <i class="fa-solid fa-user" style="color:#6366f1; margin-right:6px;"></i>Nama Lengkap <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ old('name', $merchant->name) }}" required autofocus>
                        @error('name') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="store_name">
                            <i class="fa-solid fa-store" style="color:#6366f1; margin-right:6px;"></i>Nama Toko <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="text" id="store_name" name="store_name" class="form-control"
                               value="{{ old('store_name', $merchant->store_name) }}" required>
                        @error('store_name') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        <i class="fa-solid fa-envelope" style="color:#6366f1; margin-right:6px;"></i>Email <span style="color:var(--danger);">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ old('email', $merchant->email) }}" required>
                    @error('email') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            <i class="fa-solid fa-phone" style="color:#6366f1; margin-right:6px;"></i>No. Telepon
                            <span style="color:var(--text-muted); font-weight:400;">(opsional)</span>
                        </label>
                        <input type="tel" id="phone" name="phone" class="form-control"
                               value="{{ old('phone', $merchant->phone) }}"
                               inputmode="numeric" pattern="[0-9]*" maxlength="15"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               placeholder="08xxxxxxxxxx">
                        @error('phone') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">
                            <i class="fa-solid fa-map-pin" style="color:#6366f1; margin-right:6px;"></i>Alamat Toko
                            <span style="color:var(--text-muted); font-weight:400;">(opsional)</span>
                        </label>
                        <input type="text" id="address" name="address" class="form-control"
                               value="{{ old('address', $merchant->address) }}"
                               placeholder="Alamat toko Anda">
                    </div>
                </div>

                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="btnSaveProfile">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ════════ TAB 2: Ubah Password ════════ --}}
    <div id="tabPassword" style="display:none;">
        <div class="premium-card">
            <div style="margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--border);">
                <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:4px;">
                    <i class="fa-solid fa-shield-halved" style="color:#6366f1; margin-right:8px;"></i>Ubah Password
                </h2>
                <p style="font-size:0.87rem; color:var(--text-muted);">
                    Pastikan password baru Anda kuat dan minimal 8 karakter.
                </p>
            </div>

            @if(session('password_error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('password_error') }}
                </div>
            @endif

            {{-- Info keamanan --}}
            <div style="background:rgba(99,102,241,0.06); border:1px solid rgba(99,102,241,0.15); border-radius:var(--r-md); padding:14px 18px; margin-bottom:24px; display:flex; gap:12px; align-items:flex-start;">
                <i class="fa-solid fa-circle-info" style="color:#6366f1; margin-top:2px;"></i>
                <div style="font-size:0.85rem; color:var(--text-secondary);">
                    <p style="font-weight:600; margin-bottom:3px;">Tips Keamanan Password</p>
                    <p>Gunakan kombinasi huruf besar, kecil, angka, dan simbol. Jangan gunakan informasi pribadi yang mudah ditebak.</p>
                </div>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" id="formChangePassword">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="current_password">
                        <i class="fa-solid fa-lock" style="color:#6366f1; margin-right:6px;"></i>Password Saat Ini <span style="color:var(--danger);">*</span>
                    </label>
                    <input type="password" id="current_password" name="current_password"
                           class="form-control" placeholder="Masukkan password lama" required>
                    @error('current_password') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                    <div class="form-group">
                        <label class="form-label" for="new_password">
                            <i class="fa-solid fa-key" style="color:#6366f1; margin-right:6px;"></i>Password Baru <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="password" id="new_password" name="new_password"
                               class="form-control" placeholder="Minimal 8 karakter" required>
                        @error('new_password') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password_confirmation">
                            <i class="fa-solid fa-shield-check" style="color:#6366f1; margin-right:6px;"></i>Konfirmasi Password Baru <span style="color:var(--danger);">*</span>
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                               class="form-control" placeholder="Ulangi password baru" required>
                    </div>
                </div>

                {{-- Strength indicator --}}
                <div style="margin-bottom:20px;">
                    <p style="font-size:0.78rem; color:var(--text-muted); margin-bottom:6px; font-weight:600;">KEKUATAN PASSWORD</p>
                    <div style="height:6px; border-radius:99px; background:var(--border); overflow:hidden;">
                        <div id="strengthBar" style="height:100%; width:0%; border-radius:99px; transition:all 0.35s ease; background:var(--danger);"></div>
                    </div>
                    <p id="strengthText" style="font-size:0.78rem; margin-top:5px; color:var(--text-muted);"></p>
                </div>

                <div style="display:flex; gap:12px; margin-top:8px;">
                    <button type="submit" class="btn btn-primary" id="btnChangePassword">
                        <i class="fa-solid fa-shield-halved"></i> Ubah Password
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function switchTab(tab) {
    const profileTab = document.getElementById('tabProfile');
    const passwordTab = document.getElementById('tabPassword');
    const btnProfile = document.getElementById('tabBtnProfile');
    const btnPassword = document.getElementById('tabBtnPassword');
    const activeStyle = 'background: linear-gradient(135deg,#6366f1,#8b5cf6); color:white; box-shadow:0 4px 12px rgba(99,102,241,0.3);';
    const inactiveStyle = 'background:transparent; color:var(--text-secondary); box-shadow:none;';

    if (tab === 'profile') {
        profileTab.style.display = 'block';
        passwordTab.style.display = 'none';
        btnProfile.style.cssText = activeStyle + 'padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s;';
        btnPassword.style.cssText = inactiveStyle + 'padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s;';
    } else {
        profileTab.style.display = 'none';
        passwordTab.style.display = 'block';
        btnProfile.style.cssText = inactiveStyle + 'padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s;';
        btnPassword.style.cssText = activeStyle + 'padding:9px 22px; border-radius:var(--r-md); font-size:0.88rem; font-weight:600; border:none; cursor:pointer; transition:all 0.2s;';
    }
}

// Password strength meter
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('new_password');
    if (!input) return;
    input.addEventListener('input', function() {
        const val = this.value;
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        let score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = [
            { w: '0%', c: '#ef4444', t: '' },
            { w: '25%', c: '#ef4444', t: '⚠ Lemah' },
            { w: '50%', c: '#f59e0b', t: '◑ Sedang' },
            { w: '75%', c: '#3b82f6', t: '● Kuat' },
            { w: '100%', c: '#10b981', t: '✔ Sangat Kuat' },
        ];
        const lvl = levels[score];
        bar.style.width = lvl.w;
        bar.style.background = lvl.c;
        text.textContent = lvl.t;
        text.style.color = lvl.c;
    });

    // Auto switch to password tab if there's a password error
    @if(session('password_error'))
        switchTab('password');
    @endif
});
</script>
@endsection
