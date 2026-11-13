<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Pasien> */
class PasienFactory extends Factory
{
    protected $model = Pasien::class;

    public function definition(): array
    {
        $gender = fake()->randomElement(['laki_laki', 'perempuan']);

        return [
            'nomor_rekam_medis' => 'RM-'.now()->format('Y').str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'nama_lengkap' => fake()->name($gender === 'laki_laki' ? 'male' : 'female'),
            'nomor_identitas' => fake()->unique()->numerify('35##############'),
            'tanggal_lahir' => fake()->dateTimeBetween('-70 years', '-5 years'),
            'jenis_kelamin' => $gender,
            'alamat' => fake()->address(),
            'nomor_telepon' => fake()->numerify('08##########'),
            'alamat_email' => fake()->optional(0.4)->safeEmail(),
        ];
    }
}
