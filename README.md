<div align="center">
  <img src="https://img.icons8.com/color/120/000000/shop.png" alt="DigiMart Logo">
  <h1>DigiMart</h1>
  <p><strong>Platform Manajemen Katalog Digital UMKM Premium</strong></p>
  <p><i>Dibuat sebagai Projek Ujian Tengah Semester (UTS) Pengembangan Web Berbasis Framework</i></p>

  [![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
  [![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
</div>

<hr>

## 📌 Tentang Projek
**DigiMart** adalah sistem informasi manajemen katalog produk berbasis web yang dirancang khusus untuk merchant UMKM. Aplikasi ini memungkinkan pemilik toko untuk mendaftar, mengelola identitas toko, serta mengatur daftar kategori dan produk secara terstruktur dengan antarmuka pengguna (UI) yang modern dan interaktif.

## ✨ Fitur Unggulan
- **🔐 Custom Authentication**: Sistem registrasi & login mandiri khusus Merchant tanpa mengganggu tabel `users` bawaan Laravel. (Isolasi data yang aman).
- **📊 Interactive Dashboard**: Visualisasi data statistik produk menggunakan **Chart.js** (Doughnut Chart) dan *card counter* yang dinamis.
- **📁 Manajemen Kategori & Produk**: Fitur CRUD (*Create, Read, Update, Delete*) lengkap dengan relasi Eloquent (`One-to-Many`).
- **🖼️ Image Management**: Upload foto produk dengan validasi ketat dan penghapusan fisik file otomatis saat data produk dihapus.
- **🔍 Smart Filter & Search**: Pencarian kategori/produk real-time dan penyaringan berdasarkan status (Aktif/Nonaktif).
- **⚡ Quick Toggle**: Tombol *inline* untuk mengubah status visibilitas produk tanpa harus masuk ke halaman edit form.
- **🎨 Premium UI/UX**: Desain antarmuka *glassmorphism*, dark-themed sidebar, serta notifikasi elegan menggunakan **SweetAlert2**.

## 🛠️ Teknologi yang Digunakan
* **Backend:** Laravel (PHP)
* **Database:** MySQL
* **Frontend:** HTML5, Vanilla CSS3 (Custom Styling)
* **Icons:** FontAwesome 6
* **Libraries:** SweetAlert2 (Popups), Chart.js (Grafik)

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan DigiMart di komputer Anda:

1. **Clone repositori ini**
   ```bash
   git clone https://github.com/yaqin66/digimart-uts.git
   cd digimart-uts
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   * Copy file `.env.example` menjadi `.env`
   * Buka file `.env` lalu sesuaikan koneksi database MySQL Anda:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=smart_catalog
     DB_USERNAME=root
     DB_PASSWORD=
     ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeding (Opsional)**
   Pastikan Anda sudah membuat database bernama `smart_catalog` di phpMyAdmin, lalu jalankan:
   ```bash
   php artisan migrate
   ```

6. **Hubungkan Storage (Wajib untuk foto produk)**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser pada `http://127.0.0.1:8000`.

---

## 🔒 Keamanan & Praktik Terbaik (Implementasi Kode)
- **Allowed Fields (Mass Assignment Protection)**: Seluruh Model Laravel dilengkapi properti `protected $fillable` untuk mencegah eksploitasi *mass-assignment*.
- **Regex Validation**: Form telepon menggunakan validasi regex `^[0-9]+$` untuk memastikan input 100% angka.
- **Password Hashing**: Semua password Merchant dienkripsi secara default menggunakan Bcrypt.

## 🎓 Credit
Dikembangkan oleh **[Muhammad Ainul Yaqin]** / **[411232022]**  
Universitas Dian Nusantara — Tahun 2024/2025
# digimart-uts
