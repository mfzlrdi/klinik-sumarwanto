<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\PemenuhanResep;
use App\Models\Pengguna;
use App\Models\RekamMedis;
use App\Models\Resep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class HalamanSmokeTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    private function siapkanAdmin(): Pengguna
    {
        $admin = $this->buatPengguna(['administrator', 'dokter']);
        Dokter::query()->create(['id_pengguna' => $admin->id_pengguna, 'status_aktif' => true]);

        $pasien = Pasien::factory()->create();
        $kunjungan = Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'id_dokter' => Dokter::query()->where('id_pengguna', $admin->id_pengguna)->value('id_dokter'),
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => 1,
            'status_kunjungan' => 'menunggu',
        ]);

        $rekamMedis = RekamMedis::query()->create([
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'hasil_pemeriksaan' => 'Normal',
            'status_rekam_medis' => 'draf',
        ]);

        $obat = Obat::factory()->create();
        $resep = Resep::query()->create([
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'id_rekam_medis' => $rekamMedis->id_rekam_medis,
            'nomor_resep' => 'R-TEST-0009',
            'status_resep' => 'difinalisasi',
        ]);
        $resep->rincianResep()->create([
            'id_obat' => $obat->id_obat,
            'nama_obat_saat_diresepkan' => $obat->nama_obat,
            'jumlah_diresepkan' => 5,
        ]);
        PemenuhanResep::query()->create(['id_resep' => $resep->id_resep, 'status_pemenuhan' => 'menunggu']);

        return $admin;
    }

    public function test_seluruh_halaman_utama_dapat_dirender_oleh_administrator(): void
    {
        $admin = $this->siapkanAdmin();
        $pasien = Pasien::query()->first();
        $kunjungan = Kunjungan::query()->first();
        $rekamMedis = RekamMedis::query()->first();
        $resep = Resep::query()->first();
        $pemenuhan = PemenuhanResep::query()->first();

        $halaman = [
            '/dashboard',
            '/pasien',
            "/pasien/{$pasien->id_pasien}",
            "/pasien/{$pasien->id_pasien}/riwayat-rekam-medis",
            '/kunjungan',
            "/kunjungan/{$kunjungan->id_kunjungan}",
            "/kunjungan/{$kunjungan->id_kunjungan}/cetak",
            "/pemeriksaan/{$kunjungan->id_kunjungan}",
            "/rekam-medis/{$rekamMedis->id_rekam_medis}",
            '/resep',
            "/resep/{$resep->id_resep}",
            '/obat',
            '/pemenuhan',
            "/pemenuhan/{$pemenuhan->id_pemenuhan_resep}",
            '/pengguna',
            '/dokter',
            '/laporan',
            '/pengaturan',
            '/catatan-aktivitas',
        ];

        foreach ($halaman as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }
}
