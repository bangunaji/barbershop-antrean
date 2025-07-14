<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 💈 Sistem Antrean Barbershop

Sistem Antrean Barbershop adalah aplikasi berbasis Laravel 12 yang dirancang untuk membantu barbershop dalam mengatur jadwal layanan, antrean pelanggan, dan pengelolaan tukang cukur. Sistem ini mendukung **role user**, khususnya untuk admin, di landing page bisa diakses guest dan juga pelanggan.

## 🎯 Fitur Utama

- Manajemen akun admin (karyawan barbershop)
- Manajemen layanan (potong rambut, shaving, dll)
- Booking antrean terjadwal & pelanggan walk-in
- Status antrean: **belum datang**, **datang**, **terlambat**, **selesai**, dan **batal**
- Nomor antrean otomatis + urutan bisa diubah manual oleh admin
- Penjadwalan jam kerja barber (barber schedule)
- Riwayat log aktivitas antrean

---

## 🛠️ Instalasi dan Setup

Ikuti langkah-langkah berikut untuk menjalankan project ini secara lokal:

### 1. Clone Repository

```bash
git clone https://github.com/fiebryhoga/barbershop-booking.git
cd barbershop-booking
````

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

Pastikan database sudah tersedia, lalu jalankan:

```bash
php artisan migrate --seed
```

Seeder akan otomatis membuat data awal layanan, barber, dan akun admin.

---

## 👤 Akun Default (Seeder)

| Role  | Email                  | Password |
| ----- | ---------------------- | -------- |
| Admin | [admin@example.com]    | password |


Silakan login ke panel admin (Filament) menggunakan akun di atas.

---

## 🔐 Akses Admin

Setelah login, admin bisa:

* Melihat & mengelola antrean harian
* Menambahkan booking manual (walk-in)
* Mengatur ulang urutan antrean
* Menandai status pelanggan
* Mengatur jadwal kerja barber
* Mengelola jenis layanan dan log aktivitas

Akses Filament Admin Panel:

```
/admin
```

contoh

```
localhost:8000/admin
```

---

## 📦 Stack Teknologi

* Laravel 12
* PHP 8.2+
* MySQL / MariaDB
* FilamentPHP v3
* TailwindCSS (default dari Filament)
* Vite

---

## 🤝 Kontribusi & 📄 Lisensi

Proyek ini dikembangkan oleh hafa tech hub. Kontribusi dan feedback sangat kami hargai. Silakan fork repo ini, buat perubahan yang dibutuhkan, dan kirim pull request.

---

```
```
