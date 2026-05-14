<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DigiMart') — Platform Katalog Digital UMKM</title>
    <meta name="description" content="DigiMart — Platform premium untuk mengelola ribuan produk dan kategori UMKM.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="app-container">

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside class="app-sidebar">
        {{-- Brand --}}
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; text-decoration:none;">
                <div class="brand-logo">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
                <span class="brand-name">DigiMart</span>
            </a>
        </div>

        {{-- Menu --}}
        <div style="padding:10px 0;">
            <p class="sidebar-section-label">Menu Utama</p>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" id="linkDashboard">
                    <span class="menu-icon"><i class="fa-solid fa-chart-pie"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}" id="linkCategories">
                    <span class="menu-icon"><i class="fa-solid fa-layer-group"></i></span>
                    <span>Kategori</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}" id="linkProducts">
                    <span class="menu-icon"><i class="fa-solid fa-box-open"></i></span>
                    <span>Produk</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a href="{{ route('profile.show') }}" id="linkProfile">
                    <span class="menu-icon"><i class="fa-solid fa-circle-user"></i></span>
                    <span>Profil Saya</span>
                </a>
            </li>
        </ul>

        {{-- Footer: Store Info --}}
        <div class="sidebar-footer">
            <div class="sidebar-footer-info">
                <div class="store-avatar">
                    {{ strtoupper(substr(session('store_name', 'D'), 0, 1)) }}
                </div>
                <div class="store-info">
                    <p class="store-name-sidebar">{{ session('store_name', 'Toko Anda') }}</p>
                    <p class="store-role">Merchant Resmi</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ══════════ MAIN ══════════ --}}
    <div class="app-main">

        {{-- Navbar --}}
        <header class="app-navbar">
            <div class="navbar-left">
                <div>
                    <p class="navbar-page-title">@yield('header_title', 'Dashboard')</p>
                </div>
            </div>
            <div class="navbar-right">
                {{-- Merchant Chip --}}
                <div class="navbar-merchant-chip" id="merchantChip">
                    <span class="merchant-dot"></span>
                    <i class="fa-solid fa-user" style="color:#6366f1; font-size:0.8rem;"></i>
                    <span>{{ session('merchant_name', 'Merchant') }}</span>
                </div>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout-nav" id="btnLogout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="content-area" id="mainContent">
            {{-- Alert biasa dihapus karena sudah digantikan oleh Toast SweetAlert2 --}}
            @yield('content')
        </main>
    </div>
</div>

{{-- SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Global Script untuk SweetAlert2 --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup Toast Notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Tangkap session flash dari Laravel untuk ditampilkan sebagai Toast
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        // Fungsi Global untuk Konfirmasi Hapus / Tindakan Bahaya
        window.confirmAction = function(e, message = "Apakah Anda yakin?", confirmBtnText = "Ya, lanjutkan!") {
            e.preventDefault();
            const form = e.target.closest('form');
            
            Swal.fire({
                title: 'Konfirmasi Tindakan',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#ef4444',
                confirmButtonText: confirmBtnText,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    container: 'premium-swal'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
</script>

</body>
</html>
