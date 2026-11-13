<?php

namespace App\Services;

use App\Enums\JenisNotifikasi;
use App\Models\Notifikasi;
use App\Models\Pengguna;

class LayananNotifikasi
{
    /**
     * Menyimpan notifikasi secara persisten untuk penerima yang berwenang.
     * Pengiriman waktu nyata dilakukan melalui polling ringan di frontend
     * sebagai mekanisme cadangan yang terdokumentasi.
     */
    public function kirim(Pengguna $penerima, JenisNotifikasi $jenis, string $judul, string $pesan, array $dataTambahan = []): Notifikasi
    {
        return Notifikasi::query()->create([
            'id_pengguna_penerima' => $penerima->id_pengguna,
            'jenis_notifikasi' => $jenis->value,
            'judul_notifikasi' => $judul,
            'pesan_notifikasi' => $pesan,
            'data_tambahan' => $dataTambahan ?: null,
        ]);
    }
}
