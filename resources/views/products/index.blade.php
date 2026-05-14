@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('header_title', 'Manajemen Produk')

@section('content')
<div class="premium-card">
    {{-- Header --}}
    <div class="flex-between mb-4">
        <div>
            <h2 style="font-size:1.25rem; margin-bottom:4px;">
                <i class="fa-solid fa-box-open" style="color:hsl(280,80%,55%); margin-right:8px;"></i>
                Daftar Produk Fisik
            </h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">
                Katalog produk <strong>{{ session('store_name') }}</strong>
            </p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary" id="btnAddProduct">
            <i class="fa-solid fa-plus"></i>
            Tambah Produk
        </a>
    </div>

    {{-- ═══ FITUR 1: Search & Filter ═══ --}}
    <form action="{{ route('products.index') }}" method="GET" id="formSearchProduct"
          style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; margin-bottom:20px; padding:16px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border-color);">

        <div style="flex:2; min-width:200px;">
            <label style="font-size:0.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Produk
            </label>
            <input type="text" name="search" class="form-control"
                   placeholder="Nama produk..." value="{{ request('search') }}" style="padding:10px 14px;">
        </div>

        <div style="flex:1; min-width:160px;">
            <label style="font-size:0.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fa-solid fa-layer-group"></i> Kategori
            </label>
            <select name="category_id" class="form-control" style="padding:10px 14px;">
                <option value="">Semua Kategori</option>
                @foreach($filterCategories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="min-width:140px;">
            <label style="font-size:0.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fa-solid fa-filter"></i> Status
            </label>
            <select name="status" class="form-control" style="padding:10px 14px;">
                <option value="">Semua</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" class="btn btn-primary" id="btnSearchProduct" style="padding:10px 20px;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search') || request('category_id') || request('status'))
                <a href="{{ route('products.index') }}" class="btn btn-secondary" style="padding:10px 16px;" title="Reset">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>

    {{-- Info hasil pencarian --}}
    @if(request('search') || request('category_id') || request('status'))
        <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:16px;">
            <i class="fa-solid fa-circle-info"></i>
            Menampilkan <strong>{{ $products->total() }}</strong> produk ditemukan
        </p>
    @endif

    {{-- Table --}}
    <div class="table-responsive">
        <table class="premium-table" id="productsTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th style="width:80px;">Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="text-align:center; width:160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $product)
                <tr>
                    <td>{{ $products->firstItem() + $index }}</td>
                    <td>
                        {{-- ═══ FITUR 2: Foto klik → halaman detail ═══ --}}
                        <a href="{{ route('products.show', $product->id) }}">
                            @if($product->photo)
                                <img src="{{ asset('storage/products/' . $product->photo) }}"
                                     alt="Foto {{ $product->name }}" class="product-thumbnail"
                                     style="transition:transform 0.2s; cursor:pointer;"
                                     onmouseover="this.style.transform='scale(1.15)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            @else
                                <div style="width:64px;height:64px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;color:hsl(245,82%,58%);border:1px solid var(--border-color);">
                                    <i class="fa-solid fa-image" style="font-size:1.3rem;"></i>
                                </div>
                            @endif
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" style="color:var(--text-main);">
                            <strong style="display:block;">{{ $product->name }}</strong>
                            <span style="font-size:0.8rem; color:var(--text-muted);">
                                {{ Str::limit($product->description, 45) ?: '—' }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <span style="background:hsl(280,80%,95%); color:hsl(280,80%,45%); padding:4px 10px; border-radius:20px; font-size:0.82rem; font-weight:600;">
                            {{ $product->category->name ?? '—' }}
                        </span>
                    </td>
                    <td style="font-weight:600; color:hsl(245,82%,58%);">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td>
                        <span style="{{ $product->stock <= 5 ? 'color:var(--danger-color); font-weight:700;' : '' }}">
                            {{ $product->stock }}
                            @if($product->stock <= 5 && $product->stock > 0)
                                <i class="fa-solid fa-triangle-exclamation" title="Stok hampir habis!"></i>
                            @elseif($product->stock == 0)
                                <span style="font-size:0.75rem;">(Habis)</span>
                            @endif
                        </span>
                    </td>
                    <td>
                        {{-- ═══ FITUR 3: Toggle Status Produk ═══ --}}
                        <form action="{{ route('products.toggle', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                id="toggleProduct{{ $product->id }}"
                                class="status-badge {{ $product->is_active ? 'status-active' : 'status-inactive' }}"
                                style="cursor:pointer; border:none; background:none; font-family:inherit; font-size:inherit; transition:all 0.2s ease;"
                                title="{{ $product->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}"
                                onclick="confirmAction(event, '{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }} produk \'{{ addslashes($product->name) }}\'?', 'Ya, Ubah Status')"
                            >
                                <i class="fa-solid {{ $product->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-edit-sm"
                               id="btnViewProduct{{ $product->id }}" style="background:hsl(280,80%,95%); color:hsl(280,80%,45%);">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit-sm"
                               id="btnEditProduct{{ $product->id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-sm" id="btnDeleteProduct{{ $product->id }}"
                                        onclick="confirmAction(event, 'Hapus produk \'{{ addslashes($product->name) }}\'? Data tidak dapat dikembalikan.', 'Ya, Hapus!')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; padding:48px; color:var(--text-muted);">
                        <i class="fa-solid fa-box-open" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        @if(request('search') || request('category_id') || request('status'))
                            Tidak ada produk yang cocok. <a href="{{ route('products.index') }}" style="color:hsl(280,80%,55%);">Reset filter</a>
                        @else
                            Belum ada produk. <a href="{{ route('products.create') }}" style="color:hsl(280,80%,55%); font-weight:600;">Tambah sekarang!</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $products->links() }}
    </div>
</div>
@endsection
