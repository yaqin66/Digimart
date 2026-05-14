@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('header_title', 'Edit Kategori')

@section('content')
<div style="max-width:680px;">
    <div class="premium-card">
        <!-- Card Header -->
        <div style="margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border-color);">
            <h2 style="font-size:1.2rem; margin-bottom:6px;">
                <i class="fa-solid fa-pen-to-square" style="color:hsl(245,82%,58%); margin-right:8px;"></i>
                Edit Kategori
            </h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">
                Perbarui data kategori: <strong>{{ $category->name }}</strong>
            </p>
        </div>

        <!-- Edit Form — uses PUT method for update -->
        <form action="{{ route('categories.update', $category->id) }}" method="POST" id="formEditCategory">
            @csrf
            @method('PUT')

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
                    value="{{ old('name', $category->name) }}"
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
                    placeholder="Jelaskan jenis produk dalam kategori ini..."
                >{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="custom-checkbox" for="is_active">
                    <input
                        type="checkbox"
                        id="is_active"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                    >
                    <div>
                        <p style="font-weight:500;">Kategori Aktif</p>
                        <p style="font-size:0.85rem; color:var(--text-muted);">Nonaktifkan kategori agar tidak dapat dipilih saat menambah produk</p>
                    </div>
                </label>
            </div>

            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit" class="btn btn-primary" id="btnUpdateCategory">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Perbarui Kategori
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary" id="btnCancelEdit">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
