<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Push ke GitHub

### 1. Buat repository GitHub

1. Masuk ke GitHub dan pilih **New repository**.
2. Tentukan nama repository dan visibilitasnya.
3. Untuk menghindari konflik awal, buat repository kosong tanpa README, `.gitignore`, atau license otomatis.
4. Salin URL HTTPS repository yang ditampilkan GitHub.

### 2. Commit dan push branch fitur

Jalankan perintah berikut dari folder proyek. Ganti URL dengan repository Anda.

```bash
git init -b main
git add .
git status
git commit -m "chore: initial project commit"
git remote add origin https://github.com/USERNAME/NAMA-REPOSITORY.git
git switch -c feature/initial-upload
git push -u origin feature/initial-upload
```

Setelah push berhasil, buka repository di GitHub dan buat **Pull Request** dari `feature/initial-upload` ke `main`. Tinjau perubahan sebelum menggabungkannya.

### Push perubahan berikutnya

Untuk mengirim perubahan yang sudah ada di branch kerja:

```bash
git status
git add README.md
git commit -m "docs: update project README"
git push
```

Jika perubahan baru perlu dipisahkan, buat branch terlebih dahulu, lalu push branch tersebut dan ajukan Pull Request:

```bash
git switch -c docs/update-project-readme
git add README.md
git commit -m "docs: update project README"
git push -u origin docs/update-project-readme
```

<details>
<summary><strong>Jika push ditolak karena branch remote memiliki commit baru</strong></summary>

Ambil perubahan terbaru dan rebase branch kerja sebelum mencoba push kembali:

```bash
git fetch origin
git status
git pull --rebase origin NAMA-BRANCH-KERJA
git push
```

Jika terjadi konflik, selesaikan konflik pada file terkait, lanjutkan proses rebase, lalu jalankan kembali tes yang sesuai.

</details>

## Keamanan dan privasi

- Jangan commit `.env`, token, kredensial, kunci aplikasi, atau data pasien.
- `.env.example` hanya template konfigurasi. Simpan nilai rahasia di `.env` lokal atau pengelola rahasia deployment.
- Gunakan data sintetis untuk pengembangan dan demo. Lindungi data kesehatan sesuai kebijakan dan peraturan yang berlaku.
- Ganti kata sandi demo, nonaktifkan akun demo, dan tinjau konfigurasi keamanan sebelum deployment.
- Koreksi catatan pemeriksaan yang telah difinalisasi harus mengikuti prosedur yang dapat ditelusuri.

## Pengembangan

Jalankan tes aplikasi dengan:

```bash
php artisan test
```

Bangun aset frontend dengan:

```bash
npm run build
```

Struktur direktori penting:

```text
app/                 Controller, model, middleware, dan logika aplikasi
database/            Migrasi, factory, dan seeder
resources/views/     Halaman Blade dan komponen tampilan
resources/js/        JavaScript frontend
resources/css/       CSS dan konfigurasi Tailwind
routes/web.php       Definisi route aplikasi
tests/               Tes aplikasi
```

---

<div align="center">

Dibuat untuk mendukung alur administrasi praktik dokter yang lebih tertata.

</div>
