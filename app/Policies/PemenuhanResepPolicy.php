<?php

namespace App\Policies;

use App\Models\PemenuhanResep;
use App\Models\Pengguna;

class PemenuhanResepPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'dokter', 'administrator');
    }

    public function view(Pengguna $pengguna, PemenuhanResep $pemenuhanResep): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'dokter', 'administrator');
    }

    public function update(Pengguna $pengguna, PemenuhanResep $pemenuhanResep): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function proses(Pengguna $pengguna, PemenuhanResep $pemenuhanResep): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }

    public function serahkan(Pengguna $pengguna, PemenuhanResep $pemenuhanResep): bool
    {
        return $pengguna->memilikiPeran('resepsionis', 'administrator');
    }
}
