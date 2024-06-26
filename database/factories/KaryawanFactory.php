<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Karyawan>
 */
class KaryawanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'id_akun' => $this->faker->numberBetween(1, 10),
            'nama' => fake()->name(),
            'gaji_harian' => fake()->numberBetween(50000, 100000),
            'bonus' => fake()->numberBetween(10000, 200000),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber()
        ];
    }
}
