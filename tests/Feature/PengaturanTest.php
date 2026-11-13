<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MembuatPengguna;
use Tests\TestCase;

class PengaturanTest extends TestCase
{
    use MembuatPengguna, RefreshDatabase;

    public function test_administrator_dapat_mengubah_logo_dan_nama_praktik(): void
    {
        $admin = $this->buatPengguna(['administrator']);

        $this->actingAs($admin)->put('/pengaturan', [
            'nama_praktik' => 'Klinik Sehat Bersama',
            'alamat_praktik' => 'Jl. Mawar No. 5',
            'nomor_telepon' => '081200000000',
            'jam_operasional' => 'Senin–Jumat, 09.00–17.00',
            'logo_ikon' => 'stethoscope',
            'logo_nama' => 'Klinik Sehat',
            'logo_subjudul' => 'dr. Sumarwanto',
            'tagline' => 'Sehat bersama kami.',
            'deskripsi_singkat' => 'Pelayanan kesehatan keluarga.',
        ])->assertRedirect();

        $this->assertDatabaseHas('pengaturan_sistem', [
            'nama_pengaturan' => 'logo_ikon',
            'nilai_pengaturan' => 'stethoscope',
        ]);
        $this->assertDatabaseHas('pengaturan_sistem', [
            'nama_pengaturan' => 'logo_nama',
            'nilai_pengaturan' => 'Klinik Sehat',
        ]);

        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertSee('Klinik Sehat');
        $this->get('/')->assertOk()->assertSee('Klinik Sehat Bersama');
    }

    public function test_ikon_logo_tidak_valid_ditolak(): void
    {
        $admin = $this->buatPengguna(['administrator']);

        $this->actingAs($admin)->put('/pengaturan', [
            'nama_praktik' => 'Praktik',
            'logo_ikon' => 'bukan-ikon',
            'logo_nama' => 'Praktik',
        ])->assertSessionHasErrors('logo_ikon');
    }

    public function test_non_administrator_tidak_dapat_mengakses_pengaturan(): void
    {
        $resepsionis = $this->buatPengguna(['resepsionis']);

        $this->actingAs($resepsionis)->get('/pengaturan')->assertForbidden();
    }
}
