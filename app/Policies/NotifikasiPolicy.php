<?php

namespace App\Policies;

use App\Models\Notifikasi;
use App\Models\Pengguna;

class NotifikasiPolicy
{
    public function view(Pengguna $pengguna, Notifikasi $notifikasi): bool
    {
        return $notifikasi->id_pengguna_penerima === $pengguna->id_pengguna;
    }

    public function tandaiDibaca(Pengguna $pengguna, Notifikasi $notifikasi): bool
    {
        return $notifikasi->id_pengguna_penerima === $pengguna->id_pengguna;
    }
}
