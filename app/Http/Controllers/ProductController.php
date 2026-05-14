<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Controller untuk CRUD Produk, dilengkapi dengan fitur unggah foto
 * dan validasi tipe file serta ukuran berkas.
 */
class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk milik merchant yang sedang login.
     */
    public function index(Request $request)
    {
        $merchantId = session('merchant_id');

        $query = Product::with('category')->where('merchant_id', $merchantId);

        // Filter pencarian by nama produk
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        // Kirim list kategori untuk dropdown filter
        $filterCategories = Category::where('merchant_id', $merchantId)->get();

        return view('products.index', compact('products', 'filterCategories'));
    }

    /**
     * Menampilkan form tambah produk.
     */
    public function create()
    {
        $merchantId = session('merchant_id');
        $categories = Category::where('merchant_id', $merchantId)
            ->where('is_active', true)
            ->get();

        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')
                ->with('error', 'Silakan buat kategori aktif terlebih dahulu sebelum menambahkan produk.');
        }

        return view('products.create', compact('categories'));
    }

    /**
     * Menampilkan detail satu produk.
     */
    public function show($id)
    {
        $merchantId = session('merchant_id');
        $product = Product::with('category')
            ->where('merchant_id', $merchantId)
            ->findOrFail($id);

        return view('products.show', compact('product'));
    }

    /**
     * Menyimpan produk baru beserta foto multimedia ke database.
     */
    public function store(Request $request)
    {
        $merchantId = session('merchant_id');

        // Validasi input termasuk validasi file unggahan foto produk
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'photo'       => 'required|image|mimes:jpeg,png,jpg,webp|max:3072', // Maksimal 3MB
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required'        => 'Nama produk wajib diisi.',
            'price.required'       => 'Harga produk wajib diisi.',
            'price.numeric'        => 'Harga produk harus berupa angka.',
            'stock.required'       => 'Stok produk wajib diisi.',
            'photo.required'       => 'Foto produk wajib diunggah sebagai bukti fisik.',
            'photo.image'          => 'Berkas harus berupa gambar.',
            'photo.mimes'          => 'Format foto harus jpeg, png, jpg, atau webp.',
            'photo.max'            => 'Ukuran foto maksimal adalah 3MB.',
        ]);

        // Pastikan kategori milik merchant ini
        $category = Category::where('merchant_id', $merchantId)
            ->where('id', $request->category_id)
            ->firstOrFail();

        // Generate unique slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Proses unggah foto (File Management)
        $photoName = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            // Simpan di storage/app/public/products
            $file->storeAs('products', $photoName, 'public');
        }

        Product::create([
            'merchant_id' => $merchantId,
            'category_id' => $category->id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'photo'       => $photoName,
            'is_active'   => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Produk beserta foto fisik berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit produk.
     */
    public function edit($id)
    {
        $merchantId = session('merchant_id');
        $product = Product::where('merchant_id', $merchantId)->findOrFail($id);
        $categories = Category::where('merchant_id', $merchantId)->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui data produk dan mengganti foto jika diunggah.
     */
    public function update(Request $request, $id)
    {
        $merchantId = session('merchant_id');
        $product = Product::where('merchant_id', $merchantId)->findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'name.required'        => 'Nama produk wajib diisi.',
            'price.required'       => 'Harga produk wajib diisi.',
            'photo.image'          => 'Berkas harus berupa gambar.',
            'photo.mimes'          => 'Format foto harus jpeg, png, jpg, atau webp.',
            'photo.max'            => 'Ukuran foto maksimal adalah 3MB.',
        ]);

        // Pastikan kategori milik merchant ini
        $category = Category::where('merchant_id', $merchantId)
            ->where('id', $request->category_id)
            ->firstOrFail();

        $data = [
            'category_id' => $category->id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_active'   => $request->has('is_active') ? true : false,
        ];

        // Update slug jika nama berubah
        if ($request->name !== $product->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        // Jika ada unggahan foto baru
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($product->photo && Storage::disk('public')->exists('products/' . $product->photo)) {
                Storage::disk('public')->delete('products/' . $product->photo);
            }

            $file = $request->file('photo');
            $photoName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('products', $photoName, 'public');
            $data['photo'] = $photoName;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk beserta berkas fotonya dari server.
     */
    public function destroy($id)
    {
        $merchantId = session('merchant_id');
        $product = Product::where('merchant_id', $merchantId)->findOrFail($id);

        if ($product->photo && Storage::disk('public')->exists('products/' . $product->photo)) {
            Storage::disk('public')->delete('products/' . $product->photo);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk beserta berkas foto berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif produk secara langsung dari tabel.
     */
    public function toggleStatus($id)
    {
        $merchantId = session('merchant_id');
        $product = Product::where('merchant_id', $merchantId)->findOrFail($id);

        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Produk '{$product->name}' berhasil {$status}.");
    }
}
