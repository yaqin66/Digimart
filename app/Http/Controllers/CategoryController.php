<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller untuk CRUD Kategori produk milik Merchant.
 * Mendukung URL yang bersih untuk list, create, dan edit.
 */
class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori milik merchant yang login.
     */
    public function index(Request $request)
    {
        $merchantId = session('merchant_id');

        $query = Category::where('merchant_id', $merchantId);

        // Filter pencarian by nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $categories = $query->latest()->paginate(10)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form tambah kategori.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Menyimpan data kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean'
        ], [
            'name.required' => 'Nama kategori wajib diisi.'
        ]);

        $merchantId = session('merchant_id');

        // Pastikan slug unik untuk merchant ini atau secara global
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Category::create([
            'merchant_id' => $merchantId,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit kategori.
     */
    public function edit($id)
    {
        $merchantId = session('merchant_id');
        $category = Category::where('merchant_id', $merchantId)->findOrFail($id);

        return view('categories.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database.
     */
    public function update(Request $request, $id)
    {
        $merchantId = session('merchant_id');
        $category = Category::where('merchant_id', $merchantId)->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi.'
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'is_active'   => $request->has('is_active') ? true : false,
        ];

        // Update slug jika nama berubah
        if ($request->name !== $category->name) {
            $slug = Str::slug($request->name);
            $originalSlug = $slug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori dari database.
     */
    public function destroy($id)
    {
        $merchantId = session('merchant_id');
        $category = Category::where('merchant_id', $merchantId)->findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif kategori secara langsung.
     */
    public function toggleStatus($id)
    {
        $merchantId = session('merchant_id');
        $category = Category::where('merchant_id', $merchantId)->findOrFail($id);

        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Kategori '{$category->name}' berhasil {$status}.");
    }
}
