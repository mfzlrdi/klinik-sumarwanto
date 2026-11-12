<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Pengguna> */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'alamat_email' => fake()->unique()->safeEmail(),
            'kata_sandi' => 'SandiDemo123!',
            'status_aktif' => true,
            'token_ingatan' => Str::random(10),
        ];
    }
}
