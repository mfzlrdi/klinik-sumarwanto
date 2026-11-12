<?php

namespace Tests\Feature;

use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AuthentikasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'dr. Sumarwanto' => ['dokter', 'administrator'],
            'Siti Aminah' => ['resepsionis'],
            'Rina Pelayanan' => ['resepsionis'],
        ] as $nama => $peran) {
            $akun = Pengguna::factory()->create(['nama_lengkap' => $nama]);
            $akun->peran()->sync(collect($peran)->map(fn (string $namaPeran): int => (int) Peran::query()->firstOrCreate(
                ['nama_peran' => $namaPeran],
                ['keterangan_peran' => 'Peran untuk pengujian'],
            )->getKey())->all());
        }

        RateLimiter::clear('email@example.test|127.0.0.1');
    }

    public function test_hal_login_menyediakan_kedua_jenis_akun(): void
    {
        $this->get('/masuk')->assertOk()->assertSee('Praktik dr. Sumarwanto')->assertSee('Dokter / Admin');
        $this->get('/masuk?akun=resepsionis')->assertOk()->assertSee('Resepsionis')->assertSee('value="resepsionis@praktik.test"', false);
    }

    public function test_pengguna_tanpa_sesi_diarahkan_ke_halaman_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/masuk');
    }

    public function test_pasangan_dokter_dan_administrator_dapat_login_dengan_satu_akun(): void
    {
        $dokter = Pengguna::query()->where('nama_lengkap', 'dr. Sumarwanto')->firstOrFail();

        $this->post('/masuk', [
            'alamat_email' => $dokter->alamat_email,
            'kata_sandi' => 'SandiDemo123!',
        ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($dokter);
        $this->assertTrue($dokter->memilikiPeran('dokter', 'administrator'));
        $this->get('/dashboard')->assertOk()->assertSee('dr. Sumarwanto')->assertSee('dokter')->assertSee('administrator')->assertSee('Pengaturan');
        $this->assertNotNull($dokter->fresh()->terakhir_masuk_pada);
    }

    public function test_resepsionis_login_dengan_kata_sandi_dan_dapat_keluar(): void
    {
        $resepsionis = Pengguna::query()->where('nama_lengkap', 'Siti Aminah')->firstOrFail();

        $this->post('/masuk', [
            'alamat_email' => $resepsionis->alamat_email,
            'kata_sandi' => 'SandiDemo123!',
        ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHasNoErrors();

        $this->assertAuthenticatedAs($resepsionis);
        $this->get('/dashboard')->assertOk()->assertSee('Resepsionis');
        $this->post('/keluar')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_kata_sandi_yang_salah_ditolak_tanpa_membuka_sesi(): void
    {
        $akun = Pengguna::factory()->create();

        $this->from('/masuk')->post('/masuk', [
            'alamat_email' => $akun->alamat_email,
            'kata_sandi' => 'bukan-kata-sandi',
        ])
            ->assertRedirect('/masuk')
            ->assertSessionHasErrors('alamat_email');

        $this->assertGuest();
    }

    public function test_akun_nonaktif_tidak_dapat_login(): void
    {
        $akun = Pengguna::factory()->create(['status_aktif' => false]);

        $this->from('/masuk')->post('/masuk', [
            'alamat_email' => $akun->alamat_email,
            'kata_sandi' => 'SandiDemo123!',
        ])->assertSessionHasErrors('alamat_email');

        $this->assertGuest();
    }

    public function test_input_form_login_divalidasi_dengan_pesan_indonesia(): void
    {
        $this->from('/masuk')->post('/masuk', [
            'alamat_email' => 'alamat-salah',
            'kata_sandi' => '',
        ])
            ->assertRedirect('/masuk')
            ->assertSessionHasErrors(['alamat_email', 'kata_sandi'])
            ->assertSessionHasErrorsIn('default', ['alamat_email' => 'Masukkan alamat email yang valid.']);

        $this->assertGuest();
    }

    public function test_peran_diatur_di_server_dan_resepsionis_tidak_mendapatkan_hak_admin(): void
    {
        $tanpaPeran = Pengguna::factory()->create();

        $this->actingAs($tanpaPeran)->get('/dashboard')->assertForbidden();

        $resepsionis = Pengguna::query()->where('nama_lengkap', 'Siti Aminah')->firstOrFail();
        $this->actingAs($resepsionis)->get('/dashboard')->assertOk()->assertDontSee('Pengaturan');
    }

    public function test_peran_dokter_dan_administrator_bisa_diberikan_ke_akun_yang_sama(): void
    {
        $peran = Pengguna::factory()->create();
        $peran->peran()->sync(Peran::query()->whereIn('nama_peran', ['dokter', 'administrator'])->pluck('id_peran'));

        $this->assertTrue($peran->fresh()->memilikiPeran('dokter', 'administrator'));
        $this->assertSame(6, \DB::table('pengguna_peran')->count());
    }

    public function test_sesi_dan_kolom_kata_sandi_sesuai_dengan_skema_autentikasi(): void
    {
        $this->assertTrue(Schema::hasTable('pengguna'));
        $this->assertTrue(Schema::hasColumns('pengguna', ['kata_sandi', 'token_ingatan', 'dibuat_pada', 'diperbarui_pada']));
        $this->assertTrue(Schema::hasTable('sessions'));
        $this->assertFalse(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('jobs'));
    }
}
