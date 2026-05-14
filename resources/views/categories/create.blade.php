@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('header_title', 'Tambah Kategori Baru')

@section('content')
<div style="max-width:680px;">
    <div class="premium-card">
        <!-- Card Header -->
        <div style="margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border-color);">
            <h2 style="font-size:1.2rem; margin-bottom:6px;">
                <i class="fa-solid fa-folder-plus" style="color:hsl(245,82%,58%); margin-right:8px;"></i>
                Tambah Kategori Produk
            </h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">
                Buat kategori baru untuk mengelompokkan produk di katalog Anda.
            </p>
        </div>

        <!-- Create Form -->
        <form action="{{ route('categories.store') }}" method="POST" id="formCreateCategory">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">
                    <i class="fa-solid fa-tag" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Nama Kategori <span style="color:var(--danger-color);">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control"
                    placeholder="Contoh: Makanan Ringan, Pakaian Wanita..."
                    value="{{ old('name') }}"
                    required
                    autofocus
                >
                @error('name')
                    <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">
                    <i class="fa-solid fa-align-left" style="color:hsl(245,82%,58%); margin-right:6px;"></i>
                    Deskripsi <span style="color:var(--text-muted); font-weight:400;">(opsional)</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    class="form-control"
                    rows="4"
                    placeholder="Jelaskan jenis produk yang masuk kategori ini..."
                >{{ old('description') }}</textarea>
                @error('description')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="custom-checkbox" for="is_active">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', '1') ? 'checked' : '' }}
                    >
                    <div>
                        <p style="font-weight:500;">Kategori Aktif</p>
                        <p style="font-size:0.85rem; color:var(--text-muted);">Kategori aktif dapat digunakan saat menambahkan produk</p>
                    </div>
                </label>
            </div>

            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit" class="btn btn-primary" id="btnSaveCategory">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Simpan Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary" id="btnCancelCategory">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
