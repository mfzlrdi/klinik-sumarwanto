<?php

namespace App\Policies;

use App\Models\Kunjungan;
use App\Models\Pengguna;

class KunjunganPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function view(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function update(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function panggil(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function mulaiKonsultasi(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function selesai(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function batalkan(Pengguna $pengguna, Kunjungan $kunjungan): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }
}
