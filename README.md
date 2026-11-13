# Sistem Informasi Administrasi Pelayanan Pasien, Praktik Dokter Umum dr. Sumarwanto

Aplikasi web untuk mengelola administrasi pasien, nomor antrean, kunjungan, pemeriksaan dokter, resep, dan riwayat pelayanan dalam satu sistem terpusat.

## Ringkasan & Arsitektur

- **Backend:** Laravel 13, PHP 8.3+ (dikembangkan di 8.4), OOP, arsitektur MVC.
- **Database:** MySQL 8.4 (`sistem_informasi_praktik_dokter`), Eloquent ORM, migrations, factories, seeders.
- **Frontend:** Blade, Tailwind CSS v4, JavaScript (Vanilla + Axios + Chart.js), ikon Lucide, Vite.
- **Autentikasi & otorisasi:** session auth, middleware peran (`role:`), policies per model.
- **Notifikasi:** disimpan persisten di tabel `notifikasi`, dibaca melalui polling ringan di navbar (cadangan terdokumentasi).

### Peran pengguna

| Peran | Kewenangan utama |
| --- | --- |
| `resepsionis` | Kelola pasien, pendaftaran kunjungan, antrean, tindak lanjut obat. |
| `dokter` | Antrean konsultasi, pemeriksaan/rekam medis, resep. |
| `administrator` | Kelola pengguna, data dokter, katalog obat, laporan, pengaturan, audit. |

Satu akun dapat memiliki lebih dari satu peran (akun dr. Sumarwanto memiliki `dokter` dan `administrator`).

## Persyaratan lingkungan

- PHP `^8.3` (direkomendasikan 8.3.16, diuji juga pada 8.4.x)
- Composer 2
- Node.js 20+ dan npm
- MySQL 8.4

## Instalasi & menjalankan (Laragon)

1. Klon/ekstrak proyek, lalu masuk ke direktori proyek.
2. Salin konfigurasi dan atur `.env`:

   ```bash
   copy .env.example .env
   ```

   Sesuaikan `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` dengan koneksi MySQL lokal.

3. Install dependency dan buat key:

   ```bash
   composer install
   php artisan key:generate
   npm install
   ```

4. Buat database dan jalankan migration + seeder:

   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS sistem_informasi_praktik_dokter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   php artisan migrate --seed
   ```

5. Bangun aset dan jalankan server:

   ```bash
   npm run build
   php artisan serve
   ```

   Buka `http://127.0.0.1:8000` (atau sesuaikan virtual host Laragon).

## Akun demo

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Dokter + Administrator | `dokter@praktik.test` | `DokterDemo123!` |
| Resepsionis | `resepsionis@praktik.test` | `ResepsionisDemo123!` |

## Kamus data

Seluruh tabel bisnis dan kolom menggunakan Bahasa Indonesia dengan format `snake_case`, kunci utama berawalan `id_`, dan waktu memakai `dibuat_pada`/`diperbarui_pada`. Tabel infrastruktur Laravel (`sessions`, `cache`, `jobs`) mengikuti konvensi framework.

| Tabel | Keterangan |
| --- | --- |
| `pengguna` | Akun yang dapat masuk aplikasi. |
| `peran` | Daftar peran (`dokter`, `resepsionis`, `administrator`). |
| `pengguna_peran` | Relasi banyak-ke-banyak akun dengan peran. |
| `dokter` | Profil profesi dokter, terhubung ke `pengguna`. |
| `pasien` | Biodata pasien dengan `nomor_rekam_medis` unik. |
| `kunjungan` | Data kunjungan + nomor antrean (unik per tanggal). |
| `rekam_medis` | Catatan pemeriksaan klinis per kunjungan. |
| `diagnosa` | Diagnosa pada rekam medis. |
| `tindakan_medis` | Tindakan medis pada rekam medis. |
| `obat` | Katalog obat. |
| `resep` | Resep obat (`draf`, `difinalisasi`, `dibatalkan`). |
| `rincian_resep` | Item obat dalam resep (snapshot nama saat diresepkan). |
| `pemenuhan_resep` | Tugas tindak lanjut obat, terpisah dari notifikasi. |
| `rincian_penyerahan_obat` | Catatan penyerahan per item (mendukung penyerahan sebagian). |
| `notifikasi` | Notifikasi persisten per penerima. |
| `surat_keterangan_sakit` | Surat keterangan sakit bernomor per kunjungan. |
| `catatan_aktivitas` | Jejak audit tindakan penting. |
| `pengaturan_sistem` | Konfigurasi informasi praktik. |

### Nilai status

- **Status kunjungan:** `menunggu`, `dipanggil`, `sedang_konsultasi`, `selesai`, `dibatalkan`.
- **Status rekam medis:** `draf`, `difinalisasi`.
- **Status resep:** `draf`, `difinalisasi`, `dibatalkan`.
- **Status pemenuhan:** `menunggu`, `sedang_diproses`, `siap_diserahkan`, `diserahkan_sebagian`, `selesai_diserahkan`, `dirujuk_ke_apotek_luar`, `terhambat`.

## Fitur yang telah selesai

- Autentikasi login/logout, proteksi route, multi-peran (dokter + administrator dalam satu akun).
- Dashboard per peran (resepsionis, dokter, administrator) dengan statistik nyata dan grafik kunjungan.
- Manajemen pasien (CRUD, pencarian, nomor rekam medis otomatis, riwayat kunjungan).
- Pendaftaran kunjungan dengan nomor antrean backend (anti-duplikasi) dan cetak tiket antrean.
- Alur antrean: panggil → mulai konsultasi → selesai → batalkan dengan validasi transisi.
- **Halaman pemeriksaan terpadu** untuk pasien yang sedang konsultasi: rekam medis (tanda vital, hasil, diagnosa, tindakan), resep multi-item (1/2/3 obat dengan tombol tambah item), serta surat keterangan sakit dalam satu ruang kerja.
- **Riwayat rekam medis pasien** untuk dokter melihat pemeriksaan-pemeriksaan sebelumnya sebelum konsultasi.
- Resep: draft, finalisasi (memicu notifikasi ke resepsionis), pembatalan, cetak resep.
- Tindak lanjut obat: perubahan status dan pencatatan penyerahan (penuh/sebagian).
- **Surat keterangan sakit**: pembuatan otomatis bernomor, pencetakan formal, dan penyimpanan riwayat.
- **Suara panggilan antrean**: bunyi "ding" + pengumuman berbahasa Indonesia ("Nomor Antrian A-001, silakan memasuki ruangan dr. Sumarwanto") diulang 2 kali, memakai suara bawaan browser (Web Speech API, gratis tanpa layanan luar).
- **Monitor TV antrean live** di `/monitor-antrean` (publik, data pasien disamarkan) menampilkan kolom Menunggu, Dipanggil, Sedang Konsultasi, dan Selesai, diperbarui otomatis.
- **Landing page company profile** di `/` (publik) berisi hero, layanan, profil dokter, alur pelayanan, jadwal, dan kontak yang menarik pengunjung.
- **Sidebar teal solid** dengan animasi: saat ditutup di desktop berubah menjadi rail ikon (logo + ikon menu tetap tampil), dan dapat dibuka/tutup lewat tombol hamburger di desktop, tablet, dan mobile.
- **Jam & tanggal live** di navbar samping ikon monitor.
- **Pengaturan fungsional**: mengubah ikon logo, nama logo, subjudul logo, identitas praktik, dan teks landing page — langsung tercermin di sidebar, navbar, dan halaman publik.
- Katalog obat, manajemen pengguna, data dokter, pengaturan sistem, dan audit aktivitas.
- Laporan kunjungan dengan filter rentang tanggal dan tampilan cetak.
- Notifikasi lonceng dengan jumlah belum dibaca (polling ringan).

## Suara panggilan & monitor TV

- Buka `/monitor-antrean` di layar TV ruang tunggu, lalu klik **Aktifkan Suara** sekali (kebijakan browser mewajibkan interaksi pengguna sebelum memutar audio).
- Suara memakai `SpeechSynthesis` bahasa Indonesia (`id-ID`) bawaan browser dan bunyi "ding" dari Web Audio API. Tidak ada layanan pihak ketiga.
- Panggilan dari dashboard dokter juga memutar suara yang sama secara otomatis.

## Menjalankan tes

```bash
php artisan test
```

Alur penting tercakup dalam feature test: autentikasi, otorisasi antar-peran, pembuatan pasien, nomor antrean, transisi status, pembuatan/finalisasi resep, notifikasi, halaman pemeriksaan, surat keterangan sakit, dan monitor antrean publik.

## Batasan yang diketahui

- Notifikasi waktu nyata memakai polling AJAX ringan (bukan WebSocket/Reverb) karena di lingkungan lokal; data tetap persisten di database.
- Pengelolaan stok obat belum diaktifkan (katalog obat hanya sebagai referensi resep).
- Koreksi resep yang telah difinalisasi dilakukan melalui alur pembatalan yang terlacak (bukan penimpaan langsung).
- Suara panggilan bergantung pada dukungan browser terhadap Web Speech API (Chrome/Edge umumnya menyediakan suara `id-ID`).
- Monitor antrean bersifat publik dengan nama disamarkan (mis. "Budi S."); tidak menampilkan data klinis.

## Keamanan & privasi

- Jangan commit `.env` atau kredensial. Gunakan `.env.example` sebagai template.
- Gunakan data sintetis untuk demo; jangan masukkan data pasien nyata.
- Ganti kata sandi demo sebelum penggunaan nyata.

---

Dibuat untuk mendukung alur administrasi praktik dokter yang lebih tertata.
