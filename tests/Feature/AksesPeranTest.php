<?php

namespace Tests\Feature;

use App\Models\Obat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class AksesPeranTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    public function test_administrator_dapat_mengelola_katalog_obat(): void
    {
        $admin = $this->buatPengguna(['administrator']);

        $this->actingAs($admin)->post('/obat', [
            'nama_obat' => 'Paracetamol',
            'satuan_obat' => 'tablet',
        ])->assertRedirect();

        $this->assertDatabaseHas('obat', ['nama_obat' => 'Paracetamol']);
    }

    public function test_resepsionis_tidak_dapat_mengelola_obat(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);

        $this->actingAs($resepsionis)->post('/obat', [
            'nama_obat' => 'Tidak Boleh',
        ])->assertForbidden();
    }

    public function test_dokter_tidak_dapat_mengakses_manajemen_pengguna(): void
    {
        $dokter = $this->buatPengguna(['dokter']);

        $this->actingAs($dokter)->get('/pengguna')->assertForbidden();
    }

    public function test_dokter_dapat_melihat_katalog_obat_tanpa_hak_kelola(): void
    {
        $dokter = $this->buatPengguna(['dokter']);
        Obat::factory()->create(['nama_obat' => 'Amoxicillin']);

        $this->actingAs($dokter)->get('/obat')->assertOk()->assertSee('Amoxicillin');
    }

    public function test_dokter_tidak_dapat_mengakses_tindak_lanjut_obat(): void
    {
        $dokter = $this->buatPengguna(['dokter']);

        $this->actingAs($dokter)->get('/pemenuhan')->assertForbidden();
    }
}
