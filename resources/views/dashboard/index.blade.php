@extends('layouts.app')
@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')

{{-- Welcome Banner --}}
<div style="background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius:20px; padding:28px 32px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:center; overflow:hidden; position:relative;">
    <div style="position:absolute; top:-40px; right:120px; width:180px; height:180px; border-radius:50%; background:rgba(255,255,255,0.07); pointer-events:none;"></div>
    <div style="position:absolute; bottom:-60px; right:-20px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05); pointer-events:none;"></div>
    <div style="position:relative; z-index:1;">
        <p style="font-size:0.8rem; color:rgba(255,255,255,0.7); font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">
            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
        </p>
        <h2 style="font-size:1.7rem; font-weight:800; color:white; margin-bottom:6px; letter-spacing:-0.5px;">
            👋 Halo, {{ session('merchant_name', 'Merchant') }}!
        </h2>
        <p style="color:rgba(255,255,255,0.82); font-size:0.95rem;">
            Selamat datang di <strong>DigiMart</strong> —
            kelola toko <strong>{{ session('store_name') }}</strong> Anda.
        </p>
    </div>
    <i class="fa-solid fa-store" style="font-size:5rem; color:rgba(255,255,255,0.12); position:relative; z-index:1; flex-shrink:0;"></i>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card" id="cardTotalCategories">
        <div class="stat-header">
            <span class="stat-label">Total Kategori</span>
            <div class="stat-icon-wrap violet"><i class="fa-solid fa-layer-group"></i></div>
        </div>
        <div class="stat-value">{{ $totalCategories }}</div>
        <div class="stat-sub">
            <i class="fa-solid fa-circle-check up"></i>
            <span>{{ $activeCategories }} aktif</span>
        </div>
    </div>

    <div class="stat-card" id="cardTotalProducts">
        <div class="stat-header">
            <span class="stat-label">Total Produk</span>
            <div class="stat-icon-wrap purple"><i class="fa-solid fa-box-open"></i></div>
        </div>
        <div class="stat-value">{{ $totalProducts }}</div>
        <div class="stat-sub">
            <i class="fa-solid fa-circle-check up"></i>
            <span>{{ $activeProducts }} aktif</span>
        </div>
    </div>
</div>

{{-- Chart Statistik --}}
<div class="premium-card" style="margin-bottom:28px;">
    <div class="flex-between mb-4">
        <div>
            <p style="font-size:1rem; font-weight:700; letter-spacing:-0.2px;">
                <i class="fa-solid fa-chart-pie" style="color:#6366f1; margin-right:8px;"></i>Statistik Produk per Kategori
            </p>
        </div>
    </div>

    @if($chartData->isEmpty())
        <div style="text-align:center; padding:32px; color:var(--text-muted);">
            <i class="fa-solid fa-chart-simple" style="font-size:2.2rem; margin-bottom:10px; display:block; opacity:0.35;"></i>
            <p style="font-size:0.88rem;">Belum ada data untuk menampilkan grafik.</p>
        </div>
    @else
        <div style="position: relative; height:280px; width:100%; display:flex; justify-content:center;">
            <canvas id="productChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('productChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! $chartLabels->toJson() !!},
                        datasets: [{
                            data: {!! $chartData->toJson() !!},
                            backgroundColor: [
                                '#6366f1', '#8b5cf6', '#3b82f6', '#10b981', '#f59e0b',
                                '#ef4444', '#ec4899', '#84cc16', '#06b6d4', '#64748b'
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: { family: "'Plus Jakarta Sans', sans-serif", size: 12, weight: '600' },
                                    color: '#6b7280',
                                    padding: 20,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            }
                        },
                        cutout: '70%',
                        layout: { padding: 10 }
                    }
                });
            });
        </script>
    @endif
</div>

{{-- Recent Data --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:22px;">

    {{-- Recent Categories --}}
    <div class="premium-card" id="cardRecentCategories">
        <div class="flex-between mb-4">
            <div>
                <p style="font-size:1rem; font-weight:700; letter-spacing:-0.2px;">
                    <i class="fa-solid fa-layer-group" style="color:#6366f1; margin-right:8px;"></i>Kategori Terbaru
                </p>
            </div>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary" style="padding:7px 14px; font-size:0.8rem;">
                Lihat Semua <i class="fa-solid fa-arrow-right" style="font-size:0.75rem;"></i>
            </a>
        </div>

        @if($recentCategories->isEmpty())
            <div style="text-align:center; padding:32px; color:var(--text-muted);">
                <i class="fa-solid fa-inbox" style="font-size:2.2rem; margin-bottom:10px; display:block; opacity:0.35;"></i>
                <p style="font-size:0.88rem;">Belum ada kategori.</p>
                <a href="{{ route('categories.create') }}" class="btn btn-primary" style="margin-top:12px; padding:8px 16px; font-size:0.82rem;">
                    + Tambah Kategori
                </a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($recentCategories as $cat)
                <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 14px; border-radius:var(--r-md); background:var(--surface-2); border:1px solid var(--border);">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px;height:34px;border-radius:8px;background:rgba(99,102,241,0.1);display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-tag" style="color:#6366f1; font-size:0.85rem;"></i>
                        </div>
                        <div>
                            <p style="font-weight:600; font-size:0.88rem;">{{ $cat->name }}</p>
                            <p style="font-size:0.75rem; color:var(--text-muted);">{{ $cat->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="status-badge {{ $cat->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Products --}}
    <div class="premium-card" id="cardRecentProducts">
        <div class="flex-between mb-4">
            <div>
                <p style="font-size:1rem; font-weight:700; letter-spacing:-0.2px;">
                    <i class="fa-solid fa-box-open" style="color:#8b5cf6; margin-right:8px;"></i>Produk Terbaru
                </p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-secondary" style="padding:7px 14px; font-size:0.8rem;">
                Lihat Semua <i class="fa-solid fa-arrow-right" style="font-size:0.75rem;"></i>
            </a>
        </div>

        @if($recentProducts->isEmpty())
            <div style="text-align:center; padding:32px; color:var(--text-muted);">
                <i class="fa-solid fa-box-open" style="font-size:2.2rem; margin-bottom:10px; display:block; opacity:0.35;"></i>
                <p style="font-size:0.88rem;">Belum ada produk.</p>
                <a href="{{ route('products.create') }}" class="btn btn-primary" style="margin-top:12px; padding:8px 16px; font-size:0.82rem;">
                    + Tambah Produk
                </a>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:8px;">
                @foreach($recentProducts as $prod)
                <a href="{{ route('products.show', $prod->id) }}" style="display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:var(--r-md); background:var(--surface-2); border:1px solid var(--border); transition:var(--transition);"
                   onmouseover="this.style.borderColor='#6366f1'; this.style.background='rgba(99,102,241,0.04)'"
                   onmouseout="this.style.borderColor='var(--border)'; this.style.background='var(--surface-2)'">
                    @if($prod->photo)
                        <img src="{{ asset('storage/products/' . $prod->photo) }}" alt="{{ $prod->name }}" class="product-thumbnail" style="width:44px;height:44px;">
                    @else
                        <div style="width:44px;height:44px;border-radius:var(--r-md);background:rgba(139,92,246,0.1);display:flex;align-items:center;justify-content:center;">
                            <i class="fa-solid fa-image" style="color:#8b5cf6; font-size:1rem;"></i>
                        </div>
                    @endif
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:600; font-size:0.88rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:var(--text-primary);">{{ $prod->name }}</p>
                        <p style="font-size:0.78rem; color:var(--text-muted);">{{ $prod->category->name ?? '-' }}</p>
                    </div>
                    <span style="font-size:0.88rem; font-weight:700; color:#6366f1; flex-shrink:0;">
                        Rp {{ number_format($prod->price, 0, ',', '.') }}
                    </span>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
