<?php

namespace Database\Seeders;

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
    }
}
