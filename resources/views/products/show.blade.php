@extends('layouts.app')

@section('title', $product->name)
@section('header_title', 'Detail Produk')

@section('content')
<div style="max-width:900px;">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:8px; margin-bottom:24px; font-size:0.9rem; color:var(--text-muted);">
        <a href="{{ route('products.index') }}" style="color:hsl(280,80%,55%); font-weight:500;">
            <i class="fa-solid fa-box-open"></i> Produk
        </a>
        <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i>
        <span>{{ $product->name }}</span>
    </div>

    <div style="display:grid; grid-template-columns:340px 1fr; gap:28px; align-items:start;">

        {{-- ═══ Foto Produk ═══ --}}
        <div>
            <div class="premium-card" style="padding:16px; text-align:center;">
                @if($product->photo)
                    <img
                        src="{{ asset('storage/products/' . $product->photo) }}"
                        alt="Foto {{ $product->name }}"
                        style="width:100%; max-height:300px; object-fit:cover; border-radius:var(--radius-md); box-shadow:var(--shadow-md);"
                        id="productMainPhoto"
                    >
                @else
                    <div style="width:100%; height:260px; border-radius:var(--radius-md); background:var(--primary-light); display:flex; flex-direction:column; align-items:center; justify-content:center; color:hsl(245,82%,58%);">
                        <i class="fa-solid fa-image" style="font-size:3.5rem; margin-bottom:12px; opacity:0.5;"></i>
                        <p style="font-size:0.9rem; color:var(--text-muted);">Belum ada foto produk</p>
                    </div>
                @endif

                <div style="margin-top:14px; display:flex; gap:8px; justify-content:center;">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary" id="btnEditThisProduct" style="flex:1;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Produk
                    </a>
                    <form action="{{ route('products.toggle', $product->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" id="btnToggleThisProduct"
                            class="btn {{ $product->is_active ? 'btn-secondary' : 'btn-primary' }}"
                            style="{{ $product->is_active ? 'border:1px solid var(--border-color);' : '' }}"
                            onclick="confirmAction(event, '{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk ini?', 'Ya, Ubah Status')">
                            <i class="fa-solid {{ $product->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                            {{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Status & Kategori Info Card --}}
            <div class="premium-card" style="margin-top:20px; padding:20px;">
                <h4 style="font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:14px;">Informasi Tambahan</h4>
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-muted);">Status</span>
                        <span class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fa-solid {{ $product->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }}"></i>
                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-muted);">Kategori</span>
                        <span style="background:hsl(280,80%,95%); color:hsl(280,80%,45%); padding:4px 10px; border-radius:20px; font-size:0.82rem; font-weight:600;">
                            {{ $product->category->name ?? '—' }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-muted);">Ditambahkan</span>
                        <span style="font-size:0.85rem;">{{ $product->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.9rem; color:var(--text-muted);">Diperbarui</span>
                        <span style="font-size:0.85rem;">{{ $product->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Info Detail Produk ═══ --}}
        <div>
            <div class="premium-card">
                <h2 style="font-size:1.6rem; font-weight:700; margin-bottom:8px;">{{ $product->name }}</h2>

                {{-- Harga besar --}}
                <div style="font-size:2rem; font-weight:700; color:hsl(245,82%,58%); margin:16px 0;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                {{-- Stok --}}
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:20px; padding:14px 18px; border-radius:var(--radius-md); background:var(--bg-base); border:1px solid var(--border-color);">
                    <i class="fa-solid fa-cubes" style="font-size:1.2rem; color:{{ $product->stock <= 5 ? 'var(--danger-color)' : 'var(--success-color)' }};"></i>
                    <div>
                        <p style="font-size:0.82rem; color:var(--text-muted); font-weight:500;">Stok Tersedia</p>
                        <p style="font-size:1.3rem; font-weight:700; color:{{ $product->stock <= 5 ? 'var(--danger-color)' : 'var(--text-main)' }};">
                            {{ $product->stock }} unit
                            @if($product->stock == 0)
                                <span style="font-size:0.85rem; background:var(--danger-light); color:var(--danger-color); padding:2px 8px; border-radius:10px; margin-left:6px;">Habis</span>
                            @elseif($product->stock <= 5)
                                <span style="font-size:0.85rem; background:hsl(40,90%,90%); color:hsl(40,90%,35%); padding:2px 8px; border-radius:10px; margin-left:6px;">Hampir Habis</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div style="margin-bottom:24px;">
                    <h4 style="font-size:0.9rem; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-muted); margin-bottom:10px;">
                        <i class="fa-solid fa-align-left" style="margin-right:6px;"></i>Deskripsi Produk
                    </h4>
                    <div style="padding:16px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border-color); line-height:1.7; font-size:0.95rem; color:var(--text-main);">
                        {{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary" id="btnEditProductDetail">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Produk
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary" id="btnBackToList">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" id="btnDeleteProductDetail"
                            style="background:var(--danger-light); color:var(--danger-color);"
                            onclick="confirmAction(event, 'Hapus produk \'{{ addslashes($product->name) }}\' secara permanen?', 'Ya, Hapus!')">
                            <i class="fa-solid fa-trash-can"></i> Hapus Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
