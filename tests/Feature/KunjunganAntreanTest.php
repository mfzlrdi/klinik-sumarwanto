<?php

namespace Tests\Feature;

use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class KunjunganAntreanTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    private function dokterAkun()
    {
        $dokter = $this->buatPengguna(['dokter']);
        Dokter::query()->create(['id_pengguna' => $dokter->id_pengguna, 'status_aktif' => true]);

        return $dokter;
    }

    public function test_resepsionis_mendaftarkan_kunjungan_dengan_nomor_antrean_berurutan(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $pasien = Pasien::factory()->create();

        $this->actingAs($resepsionis)->post('/kunjungan', [
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today()->format('Y-m-d'),
            'keluhan_awal' => 'Demam',
        ])->assertRedirect();

        $this->actingAs($resepsionis)->post('/kunjungan', [
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today()->format('Y-m-d'),
        ])->assertRedirect();

        $nomor = Kunjungan::query()->pluck('nomor_antrean')->sort()->values()->all();
        $this->assertSame([1, 2], $nomor);
    }

    public function test_kunjungan_menyimpan_keluhan_awal_dan_status_menunggu(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $pasien = Pasien::factory()->create();

        $this->actingAs($resepsionis)->post('/kunjungan', [
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today()->format('Y-m-d'),
            'keluhan_awal' => 'Sakit kepala',
        ])->assertRedirect();

        $this->assertDatabaseHas('kunjungan', [
            'id_pasien' => $pasien->id_pasien,
            'keluhan_awal' => 'Sakit kepala',
            'status_kunjungan' => 'menunggu',
        ]);
    }

    public function test_dokter_dapat_memanggil_dan_memulai_konsultasi(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create();
        $kunjungan = Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => 1,
            'status_kunjungan' => StatusKunjungan::Menunggu,
        ]);

        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/panggil")->assertRedirect();
        $this->assertSame('dipanggil', $kunjungan->fresh()->status_kunjungan->value);

        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/mulai-konsultasi")->assertRedirect();
        $this->assertSame('sedang_konsultasi', $kunjungan->fresh()->status_kunjungan->value);
    }

    public function test_transisi_status_tidak_valid_ditolak(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create();
        $kunjungan = Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => 1,
            'status_kunjungan' => StatusKunjungan::Menunggu,
        ]);

        // Dari "menunggu" tidak boleh langsung "selesai".
        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/selesai")->assertStatus(422);
        $this->assertSame('menunggu', $kunjungan->fresh()->status_kunjungan->value);
    }

    public function test_resepsionis_dapat_membatalkan_kunjungan(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $pasien = Pasien::factory()->create();
        $kunjungan = Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => 1,
            'status_kunjungan' => StatusKunjungan::Menunggu,
        ]);

        $this->actingAs($resepsionis)->post("/kunjungan/{$kunjungan->id_kunjungan}/batalkan", [
            'alasan_pembatalan' => 'Pasien berhalangan hadir.',
        ])->assertRedirect();

        $this->assertSame('dibatalkan', $kunjungan->fresh()->status_kunjungan->value);
    }
}
