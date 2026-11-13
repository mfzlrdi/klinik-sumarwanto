<?php

namespace Tests\Feature;

use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Pengguna;
use App\Models\RekamMedis;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class PemeriksaanSuratTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    private function dokterAkun(): Pengguna
    {
        $dokter = $this->buatPengguna(['dokter']);
        Dokter::query()->create(['id_pengguna' => $dokter->id_pengguna, 'status_aktif' => true]);

        return $dokter;
    }

    private function kunjungan(Pasien $pasien, string $status = 'sedang_konsultasi', int $nomor = 1): Kunjungan
    {
        return Kunjungan::query()->create([
            'id_pasien' => $pasien->id_pasien,
            'tanggal_kunjungan' => today(),
            'nomor_antrean' => $nomor,
            'status_kunjungan' => $status,
        ]);
    }

    public function test_dokter_dapat_membuka_halaman_pemeriksaan(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create(['nama_lengkap' => 'Budi Santoso']);
        $kunjungan = $this->kunjungan($pasien);

        $this->actingAs($dokter)->get("/pemeriksaan/{$kunjungan->id_kunjungan}")
            ->assertOk()
            ->assertSee('Pemeriksaan (RME)')
            ->assertSee('Resep Obat')
            ->assertSee('Surat Sakit');
    }

    public function test_resepsionis_tidak_dapat_membuka_halaman_pemeriksaan(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        $pasien = Pasien::factory()->create();
        $kunjungan = $this->kunjungan($pasien);

        $this->actingAs($resepsionis)->get("/pemeriksaan/{$kunjungan->id_kunjungan}")->assertForbidden();
    }

    public function test_panggil_mengembalikan_json_nomor_antrean_untuk_suara(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create();
        $kunjungan = $this->kunjungan($pasien, 'menunggu', 1);

        $this->actingAs($dokter)
            ->postJson("/kunjungan/{$kunjungan->id_kunjungan}/panggil")
            ->assertOk()
            ->assertJsonPath('kode_antrean', 'A-001')
            ->assertJsonPath('status', 'dipanggil')
            ->assertJsonStructure(['kode_antrean', 'nama_dokter', 'status']);
    }

    public function test_monitor_antrean_publik_menampilkan_data_tersamar(): void
    {
        $pasien = Pasien::factory()->create(['nama_lengkap' => 'Budi Santoso']);
        $kunjungan = $this->kunjungan($pasien, 'dipanggil', 1);

        $this->get('/monitor-antrean')->assertOk();

        $this->getJson('/monitor-antrean/data')
            ->assertOk()
            ->assertJsonPath('aktif.kode', 'A-001')
            ->assertJsonPath('aktif.nama', 'Budi S.')
            ->assertJsonMissing(['nama' => 'Budi Santoso']);
    }

    public function test_dokter_membuat_surat_keterangan_sakit_dan_mencetak(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create();
        $kunjungan = $this->kunjungan($pasien);

        $this->actingAs($dokter)->post("/kunjungan/{$kunjungan->id_kunjungan}/surat-sakit", [
            'tanggal_mulai' => today()->format('Y-m-d'),
            'tanggal_selesai' => today()->addDays(2)->format('Y-m-d'),
            'diagnosa_ringkas' => 'Demam akut',
            'keterangan' => 'Perlu istirahat total.',
            'konteks' => 'pemeriksaan',
        ])->assertRedirect();

        $this->assertDatabaseHas('surat_keterangan_sakit', [
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'diagnosa_ringkas' => 'Demam akut',
        ]);

        $surat = $kunjungan->suratKeteranganSakit()->firstOrFail();

        $this->actingAs($dokter)->get("/surat-sakit/{$surat->id_surat_keterangan_sakit}/cetak")
            ->assertOk()
            ->assertSee('Surat Keterangan Sakit')
            ->assertSee($pasien->nama_lengkap);
    }

    public function test_riwayat_rekam_medis_pasien_menampilkan_pemeriksaan_sebelumnya(): void
    {
        $dokter = $this->dokterAkun();
        $pasien = Pasien::factory()->create();

        $kunjunganLama = $this->kunjungan($pasien, 'selesai', 1);
        $rm = RekamMedis::query()->create([
            'id_kunjungan' => $kunjunganLama->id_kunjungan,
            'hasil_pemeriksaan' => 'Pemeriksaan lama',
            'status_rekam_medis' => 'difinalisasi',
        ]);
        $rm->diagnosa()->create(['nama_diagnosa' => 'Hipertensi', 'jenis_diagnosa' => 'utama']);

        $this->actingAs($dokter)->get("/pasien/{$pasien->id_pasien}/riwayat-rekam-medis")
            ->assertOk()
            ->assertSee('Hipertensi')
            ->assertSee('Riwayat Rekam Medis');
    }
}
