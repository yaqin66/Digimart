@extends('layouts.app')

@section('title', 'Daftar Kategori')
@section('header_title', 'Manajemen Kategori')

@section('content')
<div class="premium-card">
    {{-- Header --}}
    <div class="flex-between mb-4">
        <div>
            <h2 style="font-size:1.25rem; margin-bottom:4px;">
                <i class="fa-solid fa-layer-group" style="color:hsl(245,82%,58%); margin-right:8px;"></i>
                Daftar Kategori Produk
            </h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">Kelola semua kategori untuk toko <strong>{{ session('store_name') }}</strong></p>
        </div>
        <a href="{{ route('categories.create') }}" class="btn btn-primary" id="btnAddCategory">
            <i class="fa-solid fa-plus"></i>
            Tambah Kategori
        </a>
    </div>

    {{-- ═══ FITUR 1: Search & Filter ═══ --}}
    <form action="{{ route('categories.index') }}" method="GET" id="formSearchCategory"
          style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; margin-bottom:20px; padding:16px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border-color);">

        <div style="flex:1; min-width:200px;">
            <label style="font-size:0.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Kategori
            </label>
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Nama kategori..."
                value="{{ request('search') }}"
                style="padding:10px 14px;"
            >
        </div>

        <div style="min-width:160px;">
            <label style="font-size:0.82rem; font-weight:600; color:var(--text-muted); display:block; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fa-solid fa-filter"></i> Status
            </label>
            <select name="status" class="form-control" style="padding:10px 14px;">
                <option value="">Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit" class="btn btn-primary" id="btnSearchCategory" style="padding:10px 20px;">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('categories.index') }}" class="btn btn-secondary" style="padding:10px 16px;" title="Reset Filter">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>

    {{-- Info hasil pencarian --}}
    @if(request('search') || request('status'))
        <p style="font-size:0.88rem; color:var(--text-muted); margin-bottom:16px;">
            <i class="fa-solid fa-circle-info"></i>
            Menampilkan <strong>{{ $categories->total() }}</strong> hasil
            @if(request('search')) untuk pencarian "<strong>{{ request('search') }}</strong>" @endif
            @if(request('status')) dengan status <strong>{{ request('status') === 'active' ? 'Aktif' : 'Nonaktif' }}</strong> @endif
        </p>
    @endif

    {{-- Table --}}
    <div class="table-responsive">
        <table class="premium-table" id="categoriesTable">
            <thead>
                <tr>
                    <th style="width:50px;">#</th>
                    <th>Nama Kategori</th>
                    <th>Deskripsi</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th style="text-align:center; width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $categories->firstItem() + $index }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $category->description ?: '—' }}
                    </td>
                    <td>
                        {{-- ═══ FITUR 3: Toggle Status Button ═══ --}}
                        <form action="{{ route('categories.toggle', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                id="toggleCategory{{ $category->id }}"
                                class="status-badge {{ $category->is_active ? 'status-active' : 'status-inactive' }}"
                                style="cursor:pointer; border:none; background:none; font-family:inherit; font-size:inherit; transition:all 0.2s ease;"
                                title="{{ $category->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}"
                                onclick="confirmAction(event, '{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }} kategori \'{{ addslashes($category->name) }}\'?', 'Ya, Ubah Status')"
                            >
                                <i class="fa-solid {{ $category->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td style="color:var(--text-muted); font-size:0.87rem;">
                        {{ $category->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-edit-sm" id="btnEditCategory{{ $category->id }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-sm" id="btnDeleteCategory{{ $category->id }}"
                                        onclick="confirmAction(event, 'Hapus kategori \'{{ addslashes($category->name) }}\'? Data tidak dapat dikembalikan.', 'Ya, Hapus!')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:48px; color:var(--text-muted);">
                        <i class="fa-solid fa-inbox" style="font-size:2.5rem; display:block; margin-bottom:12px; opacity:0.4;"></i>
                        @if(request('search') || request('status'))
                            Tidak ada kategori yang cocok dengan filter.
                            <a href="{{ route('categories.index') }}" style="color:hsl(245,82%,58%);">Reset filter</a>
                        @else
                            Belum ada kategori. <a href="{{ route('categories.create') }}" style="color:hsl(245,82%,58%); font-weight:600;">Tambah sekarang!</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-container">
        {{ $categories->links() }}
    </div>
</div>
@endsection
