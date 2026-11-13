<?php

namespace Tests\Concerns;

use App\Models\Pengguna;
use App\Models\Peran;

trait MembuatPengguna
{
    protected function buatPengguna(array $peran = [], array $atribut = []): Pengguna
    {
        $pengguna = Pengguna::factory()->create($atribut);

        if ($peran !== []) {
            $ids = collect($peran)->map(fn (string $nama): int => (int) Peran::query()->firstOrCreate(
                ['nama_peran' => $nama],
                ['keterangan_peran' => 'Peran untuk pengujian'],
            )->getKey())->all();

            $pengguna->peran()->sync($ids);
        }

        return $pengguna;
    }
}
