<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pengguna;
use App\Models\RekamMedis;
use App\Models\Resep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class RekamMedisResepTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    private function dokterAkun(): Pengguna
    {
        $dokter = $this->buatPengguna(['dokter']);
        Dokter::query()->create(['id_pengguna' => $dokter->id_pengguna, 'status_aktif' => true]);

        return $dokter;
    }

    private function kunjunganAktif(): Kunjungan
    {
        $pasien = Pasien::factory()->create();

        return Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => 1,
            'status_kunjungan' => 'menunggu',
        ]);
    }

    public function test_dokter_membuat_rekam_medis_terhubung_ke_kunjungan(): void
    {
        $dokter = $this->dokterAkun();
        $kunjungan = $this->kunjunganAktif();

        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/rekam-medis", [
            'hasil_pemeriksaan' => 'Pemeriksaan fisik normal.',
            'tanda_vital' => ['suhu' => '36.5', 'nadi' => '80'],
            'diagnosa' => [['nama_diagnosa' => 'Hipertensi', 'jenis_diagnosa' => 'utama']],
            'tindakan' => [['nama_tindakan' => 'Pemberian obat']],
        ])->assertRedirect();

        $this->assertDatabaseHas('rekam_medis', ['id_kunjungan' => $kunjungan->id_kunjungan, 'status_rekam_medis' => 'draf']);
        $this->assertDatabaseHas('diagnosa', ['nama_diagnosa' => 'Hipertensi']);
        $this->assertDatabaseHas('tindakan_medis', ['nama_tindakan' => 'Pemberian obat']);
    }

    public function test_resepsionis_tidak_dapat_mengakses_rekam_medis(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $dokter = $this->dokterAkun();
        $kunjungan = $this->kunjunganAktif();

        $rekamMedis = RekamMedis::query()->create([
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'id_dokter' => $dokter->dokter?->id_dokter,
            'hasil_pemeriksaan' => 'Rahasia klinis',
            'status_rekam_medis' => 'draf',
        ]);

        $this->actingAs($resepsionis)->get("/rekam-medis/{$rekamMedis->id_rekam_medis}")->assertForbidden();
    }

    public function test_draf_resep_tidak_mengirim_notifikasi(): void
    {
        $dokter = $this->dokterAkun();
        $this->buatPengguna(['resepsionis']);
        $kunjungan = $this->kunjunganAktif();
        $obat = Obat::factory()->create();

        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/resep", [
            'rincian' => [[
                'id_obat' => $obat->id_obat,
                'dosis' => '1 x 500 mg',
                'jumlah_diresepkan' => 10,
            ]],
        ])->assertRedirect();

        $this->assertDatabaseCount('notifikasi', 0);
    }

    public function test_finalisasi_resep_mengirim_notifikasi_ke_resepsionis(): void
    {
        $dokter = $this->dokterAkun();
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $kunjungan = $this->kunjunganAktif();
        $obat = Obat::factory()->create();

        $resep = Resep::query()->create([
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'id_dokter' => $dokter->dokter?->id_dokter,
            'nomor_resep' => 'R-TEST-0001',
            'status_resep' => 'draf',
        ]);
        $resep->rincianResep()->create([
            'id_obat' => $obat->id_obat,
            'nama_obat_saat_diresepkan' => $obat->nama_obat,
            'jumlah_diresepkan' => 10,
        ]);

        $this->actingAs($dokter)->post("/resep/{$resep->id_resep}/finalisasi")->assertRedirect();

        $this->assertSame('difinalisasi', $resep->fresh()->status_resep->value);
        $this->assertDatabaseHas('notifikasi', [
            'id_pengguna_penerima' => $resepsionis->id_pengguna,
            'jenis_notifikasi' => 'resep_difinalisasi',
        ]);
        $this->assertDatabaseHas('pemenuhan_resep', ['id_resep' => $resep->id_resep, 'status_pemenuhan' => 'menunggu']);
    }

    public function test_resepsionis_tidak_dapat_mengubah_resep(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $dokter = $this->dokterAkun();
        $kunjungan = $this->kunjunganAktif();
        $obat = Obat::factory()->create();

        $resep = Resep::query()->create([
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'id_dokter' => $dokter->dokter?->id_dokter,
            'nomor_resep' => 'R-TEST-0002',
            'status_resep' => 'draf',
        ]);

        $this->actingAs($resepsionis)->post("/resep/{$resep->id_resep}/finalisasi")->assertForbidden();
        $this->assertSame('draf', $resep->fresh()->status_resep->value);
    }
}
