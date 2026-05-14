@extends('layouts.app')

@section('title', 'Edit Produk')
@section('header_title', 'Edit Produk')

@section('content')
<div style="max-width:760px;">
    <div class="premium-card">
        <!-- Card Header -->
        <div style="margin-bottom:28px; padding-bottom:20px; border-bottom:1px solid var(--border-color);">
            <h2 style="font-size:1.2rem; margin-bottom:6px;">
                <i class="fa-solid fa-pen-to-square" style="color:hsl(280,80%,55%); margin-right:8px;"></i>
                Edit Produk
            </h2>
            <p style="color:var(--text-muted); font-size:0.9rem;">
                Memperbarui data produk: <strong>{{ $product->name }}</strong>
            </p>
        </div>

        <!--
            enctype="multipart/form-data" tetap diperlukan karena ada opsional
            penggantian foto produk.
        -->
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="formEditProduct">
            @csrf
            @method('PUT')

            {{-- Row: Nama & Kategori --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div class="form-group">
                    <label class="form-label" for="name">
                        <i class="fa-solid fa-box" style="color:hsl(280,80%,55%); margin-right:6px;"></i>
                        Nama Produk <span style="color:var(--danger-color);">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $product->name) }}"
                        required
                        autofocus
                    >
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="category_id">
                        <i class="fa-solid fa-layer-group" style="color:hsl(280,80%,55%); margin-right:6px;"></i>
                        Kategori <span style="color:var(--danger-color);">*</span>
                    </label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Row: Harga & Stok --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div class="form-group">
                    <label class="form-label" for="price">
                        <i class="fa-solid fa-tag" style="color:hsl(280,80%,55%); margin-right:6px;"></i>
                        Harga (Rp) <span style="color:var(--danger-color);">*</span>
                    </label>
                    <input type="number" id="price" name="price" class="form-control"
                        value="{{ old('price', $product->price) }}" min="0" step="500" required>
                    @error('price') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stock">
                        <i class="fa-solid fa-cubes" style="color:hsl(280,80%,55%); margin-right:6px;"></i>
                        Stok <span style="color:var(--danger-color);">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" class="form-control"
                        value="{{ old('stock', $product->stock) }}" min="0" required>
                    @error('stock') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Foto Produk (Opsional saat edit) --}}
            <div class="form-group">
                <label class="form-label">
                    <i class="fa-solid fa-camera" style="color:hsl(280,80%,55%); margin-right:6px;"></i>
                    Foto Produk
                </label>

                {{-- Foto saat ini --}}
                @if($product->photo)
                    <div style="display:flex; align-items:center; gap:14px; padding:14px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border-color); margin-bottom:12px;">
                        <img src="{{ asset('storage/products/' . $product->photo) }}"
                             alt="Foto saat ini" class="product-thumbnail" style="width:80px; height:80px;">
                        <div>
                            <p style="font-weight:600; font-size:0.9rem;">Foto Saat Ini</p>
                            <p style="font-size:0.82rem; color:var(--text-muted);">Unggah foto baru di bawah untuk menggantinya</p>
                        </div>
                    </div>
                @endif

                <div style="border:2px dashed var(--border-color); border-radius:var(--radius-md); padding:20px; text-align:center; background:var(--bg-base); cursor:pointer; transition:var(--transition-fast);"
                     onclick="document.getElementById('photo').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.8rem; color:hsl(280,80%,55%); margin-bottom:8px; display:block;"></i>
                    <p style="font-weight:500; font-size:0.9rem; margin-bottom:2px;">
                        {{ $product->photo ? 'Klik untuk ganti foto' : 'Klik untuk pilih foto' }}
                    </p>
                    <p style="font-size:0.8rem; color:var(--text-muted);">JPEG, PNG, JPG, WebP &bull; Maks. 3 MB</p>
                    <input type="file" id="photo" name="photo"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        style="display:none;" onchange="previewPhoto(this)">
                </div>

                {{-- Preview --}}
                <div id="photoPreviewContainer" style="display:none; margin-top:14px; align-items:center; gap:14px; padding:14px; background:var(--bg-base); border-radius:var(--radius-md); border:1px solid var(--border-color);">
                    <img id="photoPreview" src="#" alt="Preview" style="width:80px;height:80px;object-fit:cover;border-radius:8px;box-shadow:var(--shadow-sm);">
                    <div>
                        <p id="photoName" style="font-weight:500; font-size:0.9rem;"></p>
                        <p id="photoSize" style="font-size:0.8rem; color:var(--text-muted);"></p>
                        <button type="button" onclick="clearPhoto()" style="color:var(--danger-color); font-size:0.85rem; margin-top:4px; background:none; border:none; cursor:pointer;">
                            <i class="fa-solid fa-xmark"></i> Hapus pilihan
                        </button>
                    </div>
                </div>
                @error('photo') <span class="form-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="custom-checkbox" for="is_active">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                        {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                    <div>
                        <p style="font-weight:500;">Produk Aktif</p>
                        <p style="font-size:0.85rem; color:var(--text-muted);">Nonaktifkan agar produk tidak tampil di katalog</p>
                    </div>
                </label>
            </div>

            <div style="display:flex; gap:12px; margin-top:8px;">
                <button type="submit" class="btn btn-primary" id="btnUpdateProduct">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Perbarui Produk
                </button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary" id="btnCancelProductEdit">
                    <i class="fa-solid fa-arrow-left"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(input) {
    const container = document.getElementById('photoPreviewContainer');
    const preview = document.getElementById('photoPreview');
    const nameEl = document.getElementById('photoName');
    const sizeEl = document.getElementById('photoSize');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            container.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }
}

function clearPhoto() {
    document.getElementById('photo').value = '';
    document.getElementById('photoPreviewContainer').style.display = 'none';
}
</script>
@endsection
