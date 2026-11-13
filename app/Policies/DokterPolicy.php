<?php

namespace App\Policies;

use App\Models\Dokter;
use App\Models\Pengguna;

class DokterPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function view(Pengguna $pengguna, Dokter $dokter): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function update(Pengguna $pengguna, Dokter $dokter): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function delete(Pengguna $pengguna, Dokter $dokter): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }
}
