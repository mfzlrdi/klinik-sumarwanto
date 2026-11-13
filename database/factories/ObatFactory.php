<?php

namespace Database\Factories;

use App\Models\Obat;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Obat> */
class ObatFactory extends Factory
{
    protected $model = Obat::class;

    public function definition(): array
    {
        return [
            'nama_obat' => fake()->unique()->word(),
            'kekuatan_obat' => fake()->optional(0.7)->randomElement(['500 mg', '250 mg', '100 mg', '50 mg']),
            'bentuk_sediaan' => fake()->randomElement(['Tablet', 'Kapsul', 'Sirup', 'Salep']),
            'satuan_obat' => fake()->randomElement(['tablet', 'kapsul', 'botol', 'tube']),
            'status_aktif' => true,
        ];
    }
}
