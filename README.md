<div align="center">

# 🟠 Aplikasi POS (Point of Sale) UMKM

### Sistem Kasir Sederhana Berbasis Web untuk Usaha Kecil & Menengah

[![PHP](https://img.shields.io/badge/PHP-7.4+-orange?style=for-the-badge&logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-blue?style=for-the-badge&logo=mysql)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-Free%20Use-success?style=for-the-badge)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Stable-success?style=for-the-badge)](https://github.com)

*Built with PHP & MySQL — Solusi Kasir Ringan untuk UMKM Indonesia*

[Fitur](#-fitur-utama) • [Instalasi](#-instalasi) • [Penggunaan](#-cara-penggunaan) • [Konfigurasi](#%EF%B8%8F-konfigurasi)

</div>

---

## 🌟 Overview

**POS UMKM** adalah aplikasi kasir modern yang dirancang khusus untuk usaha kecil dan menengah. Dengan antarmuka yang intuitif, fitur lengkap, dan performa cepat, aplikasi ini siap membantu manajemen penjualan dan stok Anda dengan mudah.

### 🎯 Tujuan

- 💼 Solusi kasir untuk toko retail kecil hingga menengah
- 📊 Manajemen stok dan penjualan terintegrasi
- 👥 Kelola data pelanggan dengan efisien
- 🖨️ Cetak nota transaksi otomatis
- 📱 Ramah perangkat (desktop, tablet, mobile)
- ⚡ Instalasi cepat tanpa konfigurasi rumit

---

## ✨ Fitur

<table>
<tr>
<td width="50%">

### 👥 Untuk Semua Pengguna
- **Halaman Kasir** - Transaksi cepat & keranjang belanja
- **Dashboard** - Pendapatan & statistik harian
- **Riwayat Transaksi** - Lihat & cetak ulang nota
- **Manajemen Stok** - Pantau ketersediaan produk
- **Manajemen Pelanggan** - Data pelanggan terintegrasi

### 🔐 Khusus Admin
- **Tambah/Edit Produk** - Kelola katalog produk
- **Tambah/Edit/Hapus Pelanggan** - Manajemen data pelanggan
- **Full Access** - Kontrol penuh ke seluruh modul

</td>
<td width="50%">

### 🎨 Desain & User Experience
- **Layout Responsif** - Desktop, tablet, mobile
- **UI Modern** - Desain bersih dengan warna orange
- **Performa Cepat** - Loading instan
- **Notifikasi Real-time** - Feedback success/error
- **Grafik Penjualan** - Visualisasi 7 hari terakhir

### 🔒 Keamanan
- **SQL Injection Protection** - Query aman
- **XSS Protection** - Input validation
- **Session Admin** - Akses terkontrol
- **Validasi Input** - Data teruji

</td>
</tr>
</table>

---

## 🛠️ Tech Stack

```
Backend      🐘 PHP 7.4+ - Server-side processing
Database     🗄️ MySQL 5.7 / MariaDB 10.4 - Data storage
Frontend     🎨 HTML5, CSS3, Vanilla JavaScript - UI/UX
Server       ⚡ Apache / Nginx - Web server
Browser      💻 Chrome, Firefox, Safari, Edge - Kompatibilitas
Storage      📁 File system & database - Data persistence
```

---

## 🚀 Instalasi

### Prerequisites

- Web Server (Apache/Nginx)
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau MariaDB 10.4
- Browser modern (Chrome, Firefox, Safari, Edge)

### Step 1: Siapkan Database

```bash
# Buat database baru
CREATE DATABASE db_kasir;

# Import file SQL (gunakan MySQL console atau phpMyAdmin)
mysql -u root -p db_kasir < db_kasir_updated.sql
```

### Step 2: Konfigurasi Database

Edit file `config.php`:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_kasir');
define('BASE_URL', 'http://localhost/pos/');
?>
```

### Step 3: Upload File ke Server

**Untuk XAMPP:**
```bash
htdocs/pos/
```

**Untuk Laragon:**
```bash
www/pos/
```

**Untuk Hosting cPanel:**
```bash
public_html/pos/
```

### Step 4: Struktur Folder

```
pos/
├── config.php              # Konfigurasi database
├── index.php               # Halaman kasir utama
├── dashboard.php           # Dashboard pendapatan
├── riwayat.php             # Riwayat transaksi
├── stok.php                # Manajemen stok
├── pelanggan.php           # Manajemen pelanggan
├── login.php               # Login admin
├── logout.php              # Logout
├── process_sale.php        # Proses transaksi
├── cetak_nota.php          # Cetak ulang nota
├── assets/
│   ├── css/
│   │   └── styles.css      # Stylesheet utama
│   └── js/
│       └── app.js          # JavaScript utama
└── db_kasir_updated.sql    # Database schema
```

### Step 5: Akses Aplikasi

Buka browser dan kunjungi:
```
http://localhost/pos/
```

---

## 📋 System Requirements

| Komponen        | Versi Minimum                 |
| --------------- | ----------------------------- |
| PHP             | 7.4+                          |
| MySQL / MariaDB | MySQL 5.7 / MariaDB 10.4      |
| Web Server      | Apache / Nginx                |
| Browser         | Chrome, Firefox, Edge, Safari |
| RAM             | 256 MB minimum                |
| Storage         | 100 MB                        |

---

## 🔑 Login Admin

**Default Credentials:**

| Username | Password  |
|----------|-----------|
| admin    | admin123  |

⚠️ **Rekomendasi:** Ubah password setelah instalasi pertama kali!

---

## 📖 Cara Penggunaan

### 💵 Melakukan Transaksi

1. **Masuk ke Halaman Kasir** - Klik menu "Kasir" di navbar
2. **Pilih Produk** - Cari dan klik produk untuk masuk keranjang
3. **Atur Jumlah** - Gunakan tombol `+` / `–` untuk sesuaikan jumlah
4. **Pilih Pelanggan** - Pilih dari dropdown pelanggan (opsional)
5. **Konfirmasi Pembayaran** - Klik tombol "Bayar"
6. **Masukkan Uang Pembayar** - Input nominal uang yang diterima
7. **Proses** - Klik tombol "Proses" untuk menyelesaikan transaksi
8. **Cetak Nota** - Nota otomatis terbuka untuk dicetak

### 📦 Menambah Produk (Admin)

1. **Login** - Masuk dengan akun admin
2. **Menu Stok** - Klik "Stok" di sidebar
3. **Tombol Tambah** - Klik "Tambah Produk"
4. **Isi Data** - Masukkan nama, harga, dan stok
5. **Simpan** - Klik tombol "Simpan"

### ✏️ Mengubah Stok Produk

1. **Buka Stok** - Klik menu "Stok"
2. **Pilih Produk** - Temukan produk yang ingin diubah
3. **Klik Edit** - Tekan tombol "Edit" atau "Ubah Stok"
4. **Update Data** - Sesuaikan jumlah stok
5. **Simpan** - Klik tombol "Simpan"

### 👤 Menambah Pelanggan (Admin)

1. **Login** - Masuk dengan akun admin
2. **Menu Pelanggan** - Klik "Pelanggan" di sidebar
3. **Tambah Pelanggan** - Klik tombol "Tambah Pelanggan Baru"
4. **Isi Data** - Masukkan nama, alamat, dan nomor telepon
5. **Simpan** - Klik tombol "Simpan"

### 🧾 Cetak Ulang Nota

1. **Buka Riwayat** - Klik menu "Riwayat"
2. **Cari Transaksi** - Gunakan filter atau pencarian
3. **Pilih Transaksi** - Klik transaksi yang ingin dicetak
4. **Cetak** - Klik tombol "Cetak" atau "Print Ulang"

### 📊 Lihat Dashboard

1. **Halaman Dashboard** - Klik "Dashboard" di menu utama
2. **Statistik Harian** - Lihat total penjualan hari ini
3. **Grafik 7 Hari** - Visualisasi penjualan mingguan
4. **Ringkasan Transaksi** - Overview akitivitas penjualan

---

## ⚙️ Konfigurasi

### 🔐 Konfigurasi Database

Edit `config.php` sesuai environment Anda:

```php
// Local Development
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');

// Production (dengan password)
define('DB_HOST', 'db.example.com');
define('DB_USER', 'db_user');
define('DB_PASS', 'secure_password_here');

define('DB_NAME', 'db_kasir');
define('BASE_URL', 'http://localhost/pos/');
```

### 🎨 Kustomisasi Desain

Edit `assets/css/styles.css`:

```css
:root {
    --color-primary: #FF8C00;      /* Orange utama */
    --color-secondary: #FFB84D;    /* Orange terang */
    --color-dark: #2C2C2C;         /* Warna gelap */
    --color-light: #F5F5F5;        /* Warna terang */
    --font-family: 'Segoe UI, sans-serif';
}
```

### 🔧 Mengubah Informasi Toko

Edit bagian header di `index.php`:

```php
<div class="store-info">
    <h1>Nama Toko Anda</h1>
    <p>Alamat Toko</p>
    <p>No. Telepon</p>
</div>
```

---

## 🗄️ Database Schema

### 📦 Tabel Produk
| Field | Type | Keterangan |
|-------|------|-----------|
| produk_id | INT (Primary Key) | ID unik produk |
| nama_produk | VARCHAR(100) | Nama produk |
| harga | DECIMAL(10,2) | Harga jual |
| stok | INT | Jumlah stok |
| created_at | TIMESTAMP | Waktu dibuat |

### 👥 Tabel Pelanggan
| Field | Type | Keterangan |
|-------|------|-----------|
| pelanggan_id | INT (Primary Key) | ID unik pelanggan |
| nama_pelanggan | VARCHAR(100) | Nama pelanggan |
| alamat | TEXT | Alamat |
| nomor_telepon | VARCHAR(15) | No. telepon |
| created_at | TIMESTAMP | Waktu dibuat |

### 🧾 Tabel Penjualan
| Field | Type | Keterangan |
|-------|------|-----------|
| penjualan_id | INT (Primary Key) | ID transaksi |
| tanggal_penjualan | DATETIME | Waktu transaksi |
| total_harga | DECIMAL(12,2) | Total harga |
| pelanggan_id | INT (Foreign Key) | ID pelanggan |
| created_at | TIMESTAMP | Waktu dibuat |

### 🛒 Tabel Detail Penjualan
| Field | Type | Keterangan |
|-------|------|-----------|
| detail_id | INT (Primary Key) | ID detail |
| penjualan_id | INT (Foreign Key) | ID transaksi |
| produk_id | INT (Foreign Key) | ID produk |
| jumlah_produk | INT | Jumlah beli |
| subtotal | DECIMAL(12,2) | Subtotal harga |

---

## 🐛 Troubleshooting

### ❗ "Koneksi database gagal"

**Solusi:**
- ✔️ Periksa file `config.php` - pastikan data benar
- ✔️ Pastikan MySQL berjalan (lihat Services/System Preferences)
- ✔️ Verifikasi database sudah dibuat dengan `SHOW DATABASES;`
- ✔️ Cek user MySQL memiliki permission yang cukup

### ❗ "Call to undefined function mysqli_connect()"

**Solusi:**
- ✔️ Aktifkan extension `extension=mysqli` pada `php.ini`
- ✔️ Restart web server (Apache/Nginx)
- ✔️ Verifikasi dengan `phpinfo()` - lihat MySQLi section

### ❗ Stok tidak berkurang setelah transaksi

**Solusi:**
- ✔️ Periksa file `process_sale.php` - cek query UPDATE stok
- ✔️ Buka Developer Tools (F12) → Console tab
- ✔️ Cek error messages di browser console
- ✔️ Verifikasi query dengan direct database query

### ❗ Nota tidak bisa dicetak

**Solusi:**
- ✔️ Izinkan popup di browser settings
- ✔️ Periksa error di JavaScript Console (F12)
- ✔️ Restart browser
- ✔️ Coba browser lain (Chrome, Firefox)

### ❗ Login tidak berfungsi

**Solusi:**
- ✔️ Hapus session/cookie browser
- ✔️ Clear browser cache (Ctrl+Shift+Delete)
- ✔️ Restart web server
- ✔️ Verifikasi user 'admin' ada di database

---

## 🔄 Backup & Maintenance

### 💾 Backup Database

```bash
# Linux/Mac
mysqldump -u root -p db_kasir > backup_db_kasir_$(date +%Y%m%d).sql

# Windows (Command Prompt)
mysqldump -u root -p db_kasir > backup_db_kasir.sql
```

### 📦 Restore Database

```bash
mysql -u root -p db_kasir < backup_db_kasir.sql
```

### 🔧 Update Massal Produk

1. Gunakan phpMyAdmin interface
2. Export data ke CSV
3. Edit di Excel/Spreadsheet
4. Import kembali ke database

### 🔐 Reset Password Admin

```sql
-- Gunakan query ini di phpMyAdmin
UPDATE users SET password = 'admin123' WHERE username = 'admin';
```

---

## 📝 Catatan Pengembangan (Roadmap)

### ✔️ Untuk Production

- [ ] Password hashing (bcrypt/argon2)
- [ ] HTTPS/SSL encryption
- [ ] Rate limiting login
- [ ] Log aktivitas admin
- [ ] Backup otomatis
- [ ] Role-based access control
- [ ] Email notifikasi
- [ ] Export laporan (Excel/PDF)

### ⭐ Fitur Tambahan Opsional

- [ ] Notifikasi stok menipis
- [ ] Multi-user support
- [ ] Payment gateway integration
- [ ] Dashboard analitik advanced
- [ ] Loyalty/Reward program
- [ ] Supplier management
- [ ] Inventory forecasting
- [ ] Multi-bahasa support
- [ ] Mobile app version
- [ ] Cloud backup

---

## 🔒 Rekomendasi Keamanan

Sebelum go live ke production, implementasikan:

1. **Password Hashing**
   ```php
   $hashed = password_hash($password, PASSWORD_BCRYPT);
   if (password_verify($input, $hashed)) { ... }
   ```

2. **Prepared Statements**
   ```php
   $stmt = $conn->prepare("SELECT * FROM produk WHERE produk_id = ?");
   $stmt->bind_param("i", $id);
   ```

3. **Input Validation**
   ```php
   $nama = filter_var($_POST['nama'], FILTER_SANITIZE_STRING);
   ```

4. **Environment Variables**
   - Gunakan `.env` file untuk sensitive data
   - Jangan commit credentials ke version control

---

## 📞 Support & Bantuan

Butuh bantuan? Hubungi kami:

- 📧 **Email** - support@posumkm.com
- 🐞 **Issue Tracker** - [Buat issue baru](https://github.com/yourusername/pos-umkm/issues)
- 💬 **Forum Diskusi** - [GitHub Discussions](https://github.com/yourusername/pos-umkm/discussions)

---

## 📄 License

Aplikasi ini bebas digunakan, dimodifikasi, dan dikembangkan untuk kebutuhan UMKM.

Lihat file [LICENSE](LICENSE) untuk detail lengkap.

---

<div align="center">

### ⭐ Beri bintang jika aplikasi ini membantu Anda!

**Dibuat dengan 🧡 untuk UMKM Indonesia**

**Versi:** 1.0.0 | **Tanggal:** Desember 2025

[Laporkan Bug](https://github.com/yourusername/pos-umkm/issues) • [Request Fitur](https://github.com/yourusername/pos-umkm/issues) • [Lihat Demo](https://posumkm.herokuapp.com)

</div>
