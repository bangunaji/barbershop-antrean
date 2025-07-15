<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📸 Sistem Pemesanan Studio Fotografi

Aplikasi **Studio Fotografi** berbasis Laravel 12 + Inertia.js + React, dirancang untuk mempermudah pengelolaan pemesanan jasa fotografi, melihat laporan pendapatan, dan memantau bukti pembayaran. Sistem ini mendukung peran **admin studio**, **fotografer**, dan **pelanggan**.

---

## 🎯 Fitur Utama

* Pemesanan layanan fotografi (Pre-wed, Studio, Outdoor, dll)
* Manajemen paket layanan & harga
* Upload bukti pembayaran pelanggan
* Galeri portofolio (admin upload, tampil di halaman publik)
* Laporan pendapatan harian/bulanan dengan fitur ekspor
* Filter pemesanan berdasarkan tanggal & paket
* Panel admin dengan FilamentPHP

---

## 🛠️ Instalasi dan Setup

Ikuti langkah-langkah berikut untuk menjalankan project ini secara lokal:

### 1. Clone Repository

```bash
git clone https://github.com/username/studio-fotografi.git
cd studio-fotografi
```

### 2. Install Dependensi

```bash
composer install
npm install && npm run dev
```

### 3. Setup Environment

```bash
cp .env.example .env
```

Edit `.env` sesuai konfigurasi database kamu, lalu jalankan:

```bash
php artisan key:generate
```

### 4. Setup Database

```bash
php artisan migrate --seed
```

Seeder akan membuat data dummy: paket foto, akun admin, dll.

---

## 👤 Akun Default (Seeder)

| Role  | Email               | Password |
| ----- | ------------------- | -------- |
| Admin | [admin@example.com] | password |

---

## 🔐 Akses Admin Panel

Login sebagai admin dan kelola data melalui:

```
/admin
```

Contoh:

```
http://localhost:8000/admin
```

---

## 🖼️ Halaman Publik

Tampilan berbasis **React + Inertia.js** (Single Page Application):

* Halaman landing: info paket foto
* Galeri: showcase portofolio hasil foto
* Form pemesanan: kirim data & upload bukti bayar
* Tanpa reload antar halaman

---

## 📦 Stack Teknologi

* Laravel 12
* PHP 8.2+
* MySQL / MariaDB
* Inertia.js + React
* FilamentPHP v3
* TailwindCSS
* Vite

---

## 📤 Fitur Ekspor Laporan

Fitur ini terdapat di admin panel:

* Ekspor laporan pendapatan ke Excel
* Filter berdasarkan tanggal & jenis paket
* Notifikasi jika ekspor gagal atau sukses

---

## 🤝 Kontribusi & Lisensi

Proyek ini dikembangkan oleh tim internal Studio Fotografi. Kontribusi sangat terbuka! Fork, modifikasi, dan ajukan pull request jika tertarik.

Lisensi: MIT.
