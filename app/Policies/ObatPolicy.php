<?php

namespace App\Policies;

use App\Models\Obat;
use App\Models\Pengguna;

class ObatPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function view(Pengguna $pengguna, Obat $obat): bool
    {
        return $pengguna->memilikiPeran('dokter', 'resepsionis', 'administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function update(Pengguna $pengguna, Obat $obat): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function delete(Pengguna $pengguna, Obat $obat): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }
}
