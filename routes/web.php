<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CatatanAktivitasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MonitorAntreanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PemenuhanResepController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\RekamMedisController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\StatusKunjunganController;
use App\Http\Controllers\SuratKeteranganSakitController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('beranda');

// Monitor antrean untuk TV ruang tunggu (data disamarkan, tanpa data klinis).
Route::get('/monitor-antrean', [MonitorAntreanController::class, 'index'])->name('antrean.monitor');
Route::get('/monitor-antrean/data', [MonitorAntreanController::class, 'data'])->name('antrean.monitor.data');

Route::middleware('guest')->group(function (): void {
    Route::get('/masuk', [LoginController::class, 'create'])->name('login');
    Route::post('/masuk', [LoginController::class, 'store'])->middleware('throttle:login')->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->middleware('role:dokter,resepsionis,administrator')->name('dashboard');
    Route::post('/keluar', [LoginController::class, 'destroy'])->name('logout');

    // Notifikasi (poling ringan).
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/tandai-semua-dibaca', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.tandai-semua');
    Route::post('/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.baca');

    // Manajemen pasien (resepsionis & administrator, dokter hanya melihat).
    Route::get('/pasien/cari', [PasienController::class, 'cari'])->middleware('role:dokter,resepsionis,administrator')->name('pasien.cari');
    Route::get('/pasien', [PasienController::class, 'index'])->middleware('role:dokter,resepsionis,administrator')->name('pasien.index');
    Route::get('/pasien/buat', [PasienController::class, 'create'])->middleware('role:resepsionis,administrator')->name('pasien.create');
    Route::post('/pasien', [PasienController::class, 'store'])->middleware('role:resepsionis,administrator')->name('pasien.store');
    Route::get('/pasien/{pasien}', [PasienController::class, 'show'])->middleware('role:dokter,resepsionis,administrator')->name('pasien.show');
    Route::get('/pasien/{pasien}/riwayat-rekam-medis', [PasienController::class, 'riwayatMedis'])->middleware('role:dokter,administrator')->name('pasien.riwayat-medis');
    Route::get('/pasien/{pasien}/edit', [PasienController::class, 'edit'])->middleware('role:resepsionis,administrator')->name('pasien.edit');
    Route::put('/pasien/{pasien}', [PasienController::class, 'update'])->middleware('role:resepsionis,administrator')->name('pasien.update');
    Route::delete('/pasien/{pasien}', [PasienController::class, 'destroy'])->middleware('role:administrator')->name('pasien.destroy');

    // Pendaftaran & antrean.
    Route::get('/kunjungan', [KunjunganController::class, 'index'])->middleware('role:dokter,resepsionis,administrator')->name('kunjungan.index');
    Route::get('/kunjungan/buat', [KunjunganController::class, 'create'])->middleware('role:resepsionis,administrator')->name('kunjungan.create');
    Route::post('/kunjungan', [KunjunganController::class, 'store'])->middleware('role:resepsionis,administrator')->name('kunjungan.store');
    Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->middleware('role:dokter,resepsionis,administrator')->name('kunjungan.show');
    Route::get('/kunjungan/{kunjungan}/cetak', [KunjunganController::class, 'cetak'])->middleware('role:dokter,resepsionis,administrator')->name('kunjungan.cetak');

    // Transisi status antrean.
    Route::post('/kunjungan/{kunjungan}/panggil', [StatusKunjunganController::class, 'panggil'])->middleware('role:dokter,administrator')->name('kunjungan.panggil');
    Route::post('/kunjungan/{kunjungan}/mulai-konsultasi', [StatusKunjunganController::class, 'mulaiKonsultasi'])->middleware('role:dokter,administrator')->name('kunjungan.mulai-konsultasi');
    Route::post('/kunjungan/{kunjungan}/selesai', [StatusKunjunganController::class, 'selesai'])->middleware('role:dokter,administrator')->name('kunjungan.selesai');
    Route::post('/kunjungan/{kunjungan}/batalkan', [StatusKunjunganController::class, 'batalkan'])->middleware('role:resepsionis,administrator')->name('kunjungan.batalkan');

    // Halaman pemeriksaan terpadu (RME + resep + surat sakit).
    Route::get('/pemeriksaan/{kunjungan}', [PemeriksaanController::class, 'show'])->middleware('role:dokter,administrator')->name('pemeriksaan.show');

    // Surat keterangan sakit (dokter & administrator).
    Route::post('/kunjungan/{kunjungan}/surat-sakit', [SuratKeteranganSakitController::class, 'store'])->middleware('role:dokter,administrator')->name('surat-sakit.store');
    Route::get('/surat-sakit/{suratKeteranganSakit}/cetak', [SuratKeteranganSakitController::class, 'cetak'])->middleware('role:dokter,administrator')->name('surat-sakit.cetak');
    Route::delete('/surat-sakit/{suratKeteranganSakit}', [SuratKeteranganSakitController::class, 'destroy'])->middleware('role:dokter,administrator')->name('surat-sakit.destroy');

    // Rekam medis (dokter & administrator).
    Route::get('/rekam-medis/{rekamMedis}', [RekamMedisController::class, 'show'])->middleware('role:dokter,administrator')->name('rekam-medis.show');
    Route::get('/kunjungan/{kunjungan}/rekam-medis/buat', [RekamMedisController::class, 'create'])->middleware('role:dokter,administrator')->name('rekam-medis.create');
    Route::post('/kunjungan/{kunjungan}/rekam-medis', [RekamMedisController::class, 'store'])->middleware('role:dokter,administrator')->name('rekam-medis.store');
    Route::get('/rekam-medis/{rekamMedis}/edit', [RekamMedisController::class, 'edit'])->middleware('role:dokter,administrator')->name('rekam-medis.edit');
    Route::put('/rekam-medis/{rekamMedis}', [RekamMedisController::class, 'update'])->middleware('role:dokter,administrator')->name('rekam-medis.update');
    Route::post('/rekam-medis/{rekamMedis}/finalisasi', [RekamMedisController::class, 'finalisasi'])->middleware('role:dokter,administrator')->name('rekam-medis.finalisasi');

    // Resep (dokter & administrator).
    Route::get('/resep', [ResepController::class, 'index'])->middleware('role:dokter,administrator')->name('resep.index');
    Route::get('/kunjungan/{kunjungan}/resep/buat', [ResepController::class, 'create'])->middleware('role:dokter,administrator')->name('resep.create');
    Route::post('/kunjungan/{kunjungan}/resep', [ResepController::class, 'store'])->middleware('role:dokter,administrator')->name('resep.store');
    Route::get('/resep/{resep}', [ResepController::class, 'show'])->middleware('role:dokter,administrator')->name('resep.show');
    Route::get('/resep/{resep}/edit', [ResepController::class, 'edit'])->middleware('role:dokter,administrator')->name('resep.edit');
    Route::put('/resep/{resep}', [ResepController::class, 'update'])->middleware('role:dokter,administrator')->name('resep.update');
    Route::post('/resep/{resep}/finalisasi', [ResepController::class, 'finalisasi'])->middleware('role:dokter,administrator')->name('resep.finalisasi');
    Route::post('/resep/{resep}/batalkan', [ResepController::class, 'batalkan'])->middleware('role:dokter,administrator')->name('resep.batalkan');
    Route::get('/resep/{resep}/cetak', [ResepController::class, 'cetak'])->middleware('role:dokter,administrator')->name('resep.cetak');

    // Katalog obat (administrator mengelola, dokter & resepsionis melihat).
    Route::get('/obat', [ObatController::class, 'index'])->middleware('role:dokter,resepsionis,administrator')->name('obat.index');
    Route::get('/obat/buat', [ObatController::class, 'create'])->middleware('role:administrator')->name('obat.create');
    Route::post('/obat', [ObatController::class, 'store'])->middleware('role:administrator')->name('obat.store');
    Route::get('/obat/{obat}/edit', [ObatController::class, 'edit'])->middleware('role:administrator')->name('obat.edit');
    Route::put('/obat/{obat}', [ObatController::class, 'update'])->middleware('role:administrator')->name('obat.update');
    Route::delete('/obat/{obat}', [ObatController::class, 'destroy'])->middleware('role:administrator')->name('obat.destroy');

    // Tindak lanjut resep (resepsionis & administrator).
    Route::get('/pemenuhan', [PemenuhanResepController::class, 'index'])->middleware('role:resepsionis,administrator')->name('pemenuhan.index');
    Route::get('/pemenuhan/{pemenuhanResep}', [PemenuhanResepController::class, 'show'])->middleware('role:resepsionis,administrator')->name('pemenuhan.show');
    Route::post('/pemenuhan/{pemenuhanResep}/status', [PemenuhanResepController::class, 'perbaruiStatus'])->middleware('role:resepsionis,administrator')->name('pemenuhan.status');
    Route::post('/pemenuhan/{pemenuhanResep}/serahkan', [PemenuhanResepController::class, 'serahkan'])->middleware('role:resepsionis,administrator')->name('pemenuhan.serahkan');

    // Manajemen akun & profil dokter (administrator).
    Route::resource('pengguna', PenggunaController::class)->except(['show'])->middleware('role:administrator');
    Route::resource('dokter', DokterController::class)->except(['show'])->middleware('role:administrator');

    // Laporan (administrator & dokter).
    Route::get('/laporan', LaporanController::class)->middleware('role:administrator,dokter')->name('laporan.index');
    Route::get('/laporan/cetak', [LaporanController::class, 'cetak'])->middleware('role:administrator,dokter')->name('laporan.cetak');

    // Pengaturan sistem & audit (administrator).
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->middleware('role:administrator')->name('pengaturan.index');
    Route::put('/pengaturan', [PengaturanController::class, 'update'])->middleware('role:administrator')->name('pengaturan.update');
    Route::get('/catatan-aktivitas', CatatanAktivitasController::class)->middleware('role:administrator')->name('catatan-aktivitas.index');
});
