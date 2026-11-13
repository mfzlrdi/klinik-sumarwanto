<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\RekamMedis;

class RekamMedisPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function view(Pengguna $pengguna, RekamMedis $rekamMedis): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function update(Pengguna $pengguna, RekamMedis $rekamMedis): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }

    public function finalisasi(Pengguna $pengguna, RekamMedis $rekamMedis): bool
    {
        return $pengguna->memilikiPeran('dokter', 'administrator');
    }
}
