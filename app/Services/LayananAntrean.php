<?php

namespace App\Services;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LayananAntrean
{
    /**
     * Mendaftarkan kunjungan baru dan memberikan nomor antrean yang konsisten
     * per tanggal operasional. Kunci cache mencegah duplikasi ketika dua
     * pendaftaran dibuat hampir bersamaan, ditambah unique constraint di skema.
     */
    public function daftarkan(Pasien $pasien, string $tanggal, ?string $keluhanAwal, Pengguna $pendaftar): Kunjungan
    {
        return Cache::lock('antrean:'.$tanggal, 10)->get(function () use ($pasien, $tanggal, $keluhanAwal, $pendaftar): Kunjungan {
            return DB::transaction(function () use ($pasien, $tanggal, $keluhanAwal, $pendaftar): Kunjungan {
                $nomor = (int) Kunjungan::query()
                    ->whereDate('tanggal_kunjungan', $tanggal)
                    ->max('nomor_antrean') + 1;

                return Kunjungan::query()->create([
                    'id_pasien' => $pasien->id_pasien,
                    'tanggal_kunjungan' => $tanggal,
                    'nomor_antrean' => $nomor,
                    'keluhan_awal' => $keluhanAwal,
                    'status_kunjungan' => 'menunggu',
                    'id_pengguna_pendaftar' => $pendaftar->id_pengguna,
                ]);
            });
        });
    }
}
