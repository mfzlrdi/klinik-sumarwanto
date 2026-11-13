<?php

namespace App\Policies;

use App\Models\Pengguna;

class PenggunaPolicy
{
    public function viewAny(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function view(Pengguna $pengguna, Pengguna $target): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function create(Pengguna $pengguna): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function update(Pengguna $pengguna, Pengguna $target): bool
    {
        return $pengguna->memilikiPeran('administrator');
    }

    public function delete(Pengguna $pengguna, Pengguna $target): bool
    {
        return $pengguna->memilikiPeran('administrator') && $pengguna->id_pengguna !== $target->id_pengguna;
    }
}
