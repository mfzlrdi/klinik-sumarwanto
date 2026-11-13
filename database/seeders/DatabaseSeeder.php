<?php

namespace Database\Seeders;

use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\PengaturanSistem;
use App\Models\Pengguna;
use App\Models\Peran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $peran = collect([
            'dokter' => 'Mengakses antrean dan dokumentasi pemeriksaan klinis.',
            'resepsionis' => 'Mengelola administrasi pasien dan pendaftaran kunjungan.',
            'administrator' => 'Mengelola konfigurasi dan akses administratif sistem.',
        ])->map(fn (string $keterangan, string $nama): Peran => Peran::updateOrCreate(
            ['nama_peran' => $nama],
            ['keterangan_peran' => $keterangan],
        ));

        $dokter = Pengguna::updateOrCreate(
            ['alamat_email' => 'dokter@praktik.test'],
            ['nama_lengkap' => 'dr. Sumarwanto', 'kata_sandi' => Hash::make('DokterDemo123!'), 'status_aktif' => true],
        );
        $dokter->peran()->sync([$peran['dokter']->id_peran, $peran['administrator']->id_peran]);

        $resepsionis = Pengguna::updateOrCreate(
            ['alamat_email' => 'resepsionis@praktik.test'],
            ['nama_lengkap' => 'Siti Aminah', 'kata_sandi' => Hash::make('ResepsionisDemo123!'), 'status_aktif' => true],
        );
        $resepsionis->peran()->sync([$peran['resepsionis']->id_peran]);

        Dokter::updateOrCreate(
            ['id_pengguna' => $dokter->id_pengguna],
            ['nomor_sip' => 'SIP-001/2026', 'nomor_telepon_profesional' => '081200000001', 'status_aktif' => true],
        );

        $this->seedObat();
        $this->seedPengaturan();

        if (Pasien::query()->count() === 0) {
            $this->seedPasien();
        }

        if (Kunjungan::query()->count() === 0) {
            $this->seedKunjungan($dokter);
        }
    }

    private function seedObat(): void
    {
        $daftar = [
            ['Paracetamol', '500 mg', 'Tablet', 'tablet'],
            ['Amoxicillin', '500 mg', 'Kapsul', 'kapsul'],
            ['Ibuprofen', '400 mg', 'Tablet', 'tablet'],
            ['Dexamethasone', '0.5 mg', 'Tablet', 'tablet'],
            ['Cetirizine', '10 mg', 'Tablet', 'tablet'],
            ['Omeprazole', '20 mg', 'Kapsul', 'kapsul'],
            ['OBH Combi', null, 'Sirup', 'botol'],
            ['Betadine', null, 'Salep', 'tube'],
        ];

        foreach ($daftar as [$nama, $kekuatan, $bentuk, $satuan]) {
            Obat::updateOrCreate(
                ['nama_obat' => $nama, 'kekuatan_obat' => $kekuatan, 'bentuk_sediaan' => $bentuk],
                ['satuan_obat' => $satuan, 'status_aktif' => true],
            );
        }
    }

    private function seedPengaturan(): void
    {
        foreach (PengaturanSistem::bawaan() as $nama => $nilai) {
            PengaturanSistem::updateOrCreate(['nama_pengaturan' => $nama], ['nilai_pengaturan' => $nilai]);
        }
    }

    private function seedPasien(): void
    {
        Pasien::factory()->count(24)->create();
    }

    private function seedKunjungan(Pengguna $dokter): void
    {
        $pasien = Pasien::query()->inRandomOrder()->take(12)->get();
        $idDokter = Dokter::query()->where('id_pengguna', $dokter->id_pengguna)->value('id_dokter');

        $keluhan = ['Demam dan batuk', 'Sakit kepala', 'Nyeri lambung', 'Alergi kulit', 'Pilek dan tenggorokan', 'Kontrol tekanan darah'];

        // Kunjungan hari ini dengan berbagai status.
        $nomor = 0;
        foreach ($pasien->take(6) as $index => $p) {
            $nomor++;
            $status = match ($index) {
                0 => StatusKunjungan::SedangKonsultasi,
                1 => StatusKunjungan::Dipanggil,
                default => StatusKunjungan::Menunggu,
            };

            Kunjungan::query()->create([
                'id_pasien' => $p->id_pasien,
                'id_dokter' => $idDokter,
                'tanggal_kunjungan' => today(),
                'nomor_antrean' => $nomor,
                'keluhan_awal' => fake()->randomElement($keluhan),
                'status_kunjungan' => $status->value,
                'id_pengguna_pendaftar' => $dokter->id_pengguna,
            ]);
        }

        // Kunjungan historis untuk laporan 7 hari terakhir.
        for ($i = 1; $i <= 7; $i++) {
            $tanggal = today()->subDays($i);
            foreach ($pasien->skip(6)->take(3) as $p) {
                $nomor++;
                Kunjungan::query()->create([
                    'id_pasien' => $p->id_pasien,
                    'id_dokter' => $idDokter,
                    'tanggal_kunjungan' => $tanggal,
                    'nomor_antrean' => $nomor,
                    'keluhan_awal' => fake()->randomElement($keluhan),
                    'status_kunjungan' => fake()->randomElement([StatusKunjungan::Selesai->value, StatusKunjungan::Selesai->value, StatusKunjungan::Dibatalkan->value]),
                    'id_pengguna_pendaftar' => $dokter->id_pengguna,
                ]);
            }
        }
    }
}
