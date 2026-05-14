<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed data awal untuk Smart-Catalog.
     */
    public function run(): void
    {
        // Buat merchant demo
        $merchant = Merchant::create([
            'name'       => 'Ahmad Merchant',
            'email'      => 'merchant@smartcatalog.com',
            'password'   => Hash::make('password123'),
            'store_name' => 'Toko Serba Ada UMKM',
            'phone'      => '081234567890',
            'address'    => 'Jl. Contoh No. 1, Jakarta Selatan',
        ]);

        // Buat kategori demo
        $categories = [
            ['name' => 'Makanan Ringan', 'description' => 'Snack, camilan, dan makanan ringan berkualitas'],
            ['name' => 'Minuman Segar',  'description' => 'Minuman dingin, jus, dan minuman kesehatan'],
            ['name' => 'Pakaian Wanita', 'description' => 'Busana, aksesoris, dan pakaian wanita tren terkini'],
            ['name' => 'Elektronik',     'description' => 'Gadget, aksesoris elektronik, dan perangkat pintar'],
            ['name' => 'Kerajinan',      'description' => 'Produk kerajinan tangan UMKM lokal berkualitas'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::create([
                'merchant_id' => $merchant->id,
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active'   => true,
            ]);
        }

        // Buat beberapa produk demo (tanpa foto, karena ini seeder)
        $products = [
            ['name' => 'Keripik Singkong Pedas',  'price' => 15000,  'stock' => 50,  'cat' => 0],
            ['name' => 'Jus Mangga Segar',          'price' => 12000,  'stock' => 30,  'cat' => 1],
            ['name' => 'Blouse Batik Modern',       'price' => 185000, 'stock' => 20,  'cat' => 2],
            ['name' => 'Smartwatch Lokal',          'price' => 350000, 'stock' => 15,  'cat' => 3],
            ['name' => 'Tas Anyaman Bambu',         'price' => 95000,  'stock' => 25,  'cat' => 4],
        ];

        foreach ($products as $prod) {
            Product::create([
                'merchant_id' => $merchant->id,
                'category_id' => $createdCategories[$prod['cat']]->id,
                'name'        => $prod['name'],
                'slug'        => Str::slug($prod['name']),
                'description' => 'Deskripsi untuk ' . $prod['name'],
                'price'       => $prod['price'],
                'stock'       => $prod['stock'],
                'photo'       => null,
                'is_active'   => true,
            ]);
        }

        $this->command->info('✅ DigiMart demo data seeded!');
        $this->command->info('📧 Login: merchant@smartcatalog.com | 🔑 Password: password123');
    }
}
