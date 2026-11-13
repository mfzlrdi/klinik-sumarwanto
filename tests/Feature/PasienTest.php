<?php

namespace Tests\Feature;

use App\Models\Pasien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class PasienTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    public function test_resepsionis_dapat_menambahkan_pasien_baru(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);

        $this->actingAs($resepsionis)->post('/pasien', [
            'nama_lengkap' => 'Budi Santoso',
            'jenis_kelamin' => 'laki_laki',
            'tanggal_lahir' => '1990-05-10',
            'nomor_telepon' => '081234567890',
        ])->assertRedirect();

        $this->assertDatabaseHas('pasien', ['nama_lengkap' => 'Budi Santoso']);

        $pasien = Pasien::query()->where('nama_lengkap', 'Budi Santoso')->firstOrFail();
        $this->assertMatchesRegularExpression('/^RM-\d{4}\d{5}$/', $pasien->nomor_rekam_medis);
    }

    public function test_input_pasien_tidak_valid_ditolak(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);

        $this->actingAs($resepsionis)->post('/pasien', [
            'nama_lengkap' => '',
        ])->assertSessionHasErrors('nama_lengkap');

        $this->assertDatabaseCount('pasien', 0);
    }

    public function test_dokter_tidak_dapat_menambahkan_pasien(): void
    {
        $dokter = $this->buatPengguna(['dokter']);

        $this->actingAs($dokter)->post('/pasien', [
            'nama_lengkap' => 'Tidak Boleh',
        ])->assertForbidden();
    }

    public function test_pencarian_pasien_mengembalikan_hasil(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);
        Pasien::factory()->create(['nama_lengkap' => 'Ahmad Fauzi']);

        $this->actingAs($resepsionis)->getJson('/pasien/cari?q=Ahmad')
            ->assertOk()
            ->assertJsonPath('hasil.0.nama', 'Ahmad Fauzi');
    }

    public function test_pasien_tanpa_login_diarahkan_ke_login(): void
    {
        $this->get('/pasien')->assertRedirect('/masuk');
    }
}
