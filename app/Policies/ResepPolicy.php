<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\Resep;

class ResepPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function view(Pengguna $pengguna, Resep $resep): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function update(Pengguna $pengguna, Resep $resep): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function finalisasi(Pengguna $pengguna, Resep $resep): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function batalkan(Pengguna $pengguna, Resep $resep): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }
}
