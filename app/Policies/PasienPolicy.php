<?php

namespace App\Policies;

use App\Models\Pasien;
use App\Models\Pengguna;

class PasienPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function view(Pengguna $pengguna, Pasien $pasien): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function update(Pengguna $pengguna, Pasien $pasien): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function delete(Pengguna $pengguna, Pasien $pasien): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }
}
