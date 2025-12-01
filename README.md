<div align="center">
🟠 Aplikasi POS (Point of Sale) UMKM
Sistem kasir sederhana berbasis web untuk usaha kecil & menengah
<br/> <p> <img src="https://img.shields.io/badge/PHP-7.4+-orange?style=for-the-badge&logo=php"/> <img src="https://img.shields.io/badge/MySQL-5.7+-blue?style=for-the-badge&logo=mysql"/> <img src="https://img.shields.io/badge/Status-Stable-success?style=for-the-badge"/> <img src="https://img.shields.io/badge/UI-Responsive-lightgrey?style=for-the-badge&logo=html5"/> </p> <br/>

Solusi kasir ringan untuk UMKM, ramah perangkat, mudah digunakan, dan cepat dipasang.

</div>
🌟 Fitur Utama
👥 Untuk Semua Pengguna

✅ Halaman Kasir (Transaksi cepat & keranjang belanja)

✅ Dashboard pendapatan & statistik harian

✅ Riwayat transaksi + cetak ulang nota

✅ Manajemen stok (view only)

✅ Manajemen pelanggan (view only)

🔐 Khusus Admin

🛒 Tambah/Edit Produk

👤 Tambah/Edit/Hapus Pelanggan

🔑 Full access ke seluruh modul

📋 Persyaratan Sistem
| Komponen        | Versi Minimum                 |
| --------------- | ----------------------------- |
| PHP             | 7.4+                          |
| MySQL / MariaDB | MySQL 5.7 / MariaDB 10.4      |
| Web Server      | Apache / Nginx                |
| Browser         | Chrome, Firefox, Edge, Safari |

🛠️ Instalasi
1️⃣ Siapkan Database
CREATE DATABASE db_kasir;
mysql -u root -p db_kasir < db_kasir_updated.sql

2️⃣ Konfigurasi Database

Edit file config.php:

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_kasir');
define('BASE_URL', 'http://localhost/pos/');

3️⃣ Upload File ke Server

Untuk XAMPP → htdocs/pos/

Untuk Laragon → www/pos/

Untuk hosting cPanel → public_html/pos/

4️⃣ Struktur Folder
pos/
├── config.php
├── index.php          (Halaman Kasir)
├── dashboard.php
├── riwayat.php
├── stok.php
├── pelanggan.php
├── login.php
├── logout.php
├── process_sale.php
├── cetak_nota.php
└── db_kasir_updated.sql

🔑 Login Admin
User	Password
admin	admin123

📖 Cara Penggunaan
💵 Melakukan Transaksi

Masuk ke halaman Kasir

Pilih produk untuk masuk keranjang

Atur jumlah dengan tombol + / –

Pilih pelanggan

Klik Bayar → masukkan uang bayar

Klik Proses

Nota otomatis terbuka

📦 Menambah Produk (Admin)

Login

Masuk menu Stok

Klik Tambah Produk

Isi data → Simpan

✏️ Mengubah Stok

Login

Buka Stok

Pilih produk → klik Ubah Stok

👤 Menambah Pelanggan

Login

Masuk ke Pelanggan

Klik Tambah Pelanggan Baru

🧾 Cetak Ulang Nota

Buka Riwayat

Cari transaksi

Klik Cetak

🎨 Fitur Desain

📱 Responsive layout

🎨 Modern orange clean UI

⚡ Performa cepat

🔔 Notifikasi aksi (success/error)

📊 Grafik penjualan 7 hari terakhir

🔒 Keamanan

✔️ SQL Injection Protection

✔️ XSS Protection

✔️ Session Admin

✔️ Validasi input

⚠️ Password hashing disarankan sebelum production

🗄️ Database Schema
📦 Tabel produk

produk_id

nama_produk

harga

stok

👥 Tabel pelanggan

pelanggan_id

nama_pelanggan

alamat

nomor_telepon

🧾 Tabel penjualan

penjualan_id

tanggal_penjualan

total_harga

pelanggan_id

🛒 Tabel detail_penjualan

detail_id

penjualan_id

produk_id

jumlah_produk

subtotal

🐛 Troubleshooting
❗ "Koneksi database gagal"

✔️ Cek config.php
✔️ Pastikan MySQL berjalan
✔️ Pastikan DB dibuat

❗ "Call to undefined function mysqli_connect()"

✔️ Aktifkan extension=mysqli pada php.ini

❗ Stok tidak berkurang

✔️ Periksa file process_sale.php
✔️ Cek console browser

❗ Nota tidak bisa dicetak

✔️ Izinkan popup
✔️ Cek error JavaScript

🔄 Update & Maintenance
🔁 Backup Database
mysqldump -u root -p db_kasir > backup_db_kasir.sql

📦 Update produk massal

Gunakan CSV di phpMyAdmin

🔧 Perbaikan login

Hapus session atau restart server

📝 Catatan Pengembangan (Roadmap)
✔️ Untuk Production

Password hashing (bcrypt/argon2)

HTTPS/SSL

Rate limiting

Log aktivitas admin

Backup otomatis

Role-based access control

Email notifikasi

Export laporan (Excel/PDF)

⭐ Fitur Tambahan Opsional

Notifikasi stok menipis

PWA (mobile)

Payment gateway

Dashboard analitik

Loyalty point

Supplier management

Multi-user

📞 Support

Buka issue

Hubungi developer

Lihat dokumentasi

📄 License

Aplikasi ini bebas digunakan, dimodifikasi, dan dikembangkan untuk kebutuhan UMKM.

<div align="center">
Dibuat dengan 🧡 untuk UMKM Indonesia

Versi: 1.0.0
Tanggal: Desember 2025

</div>


