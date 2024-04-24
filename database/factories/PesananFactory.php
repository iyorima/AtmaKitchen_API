<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pesanan>
 */
class PesananFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tgl_order' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'verified_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'jenis_pengiriman' => fake()->randomKey(['Kurir Toko' => 1, 'Kurir Ojol' => 2, 'Ambil Sendiri' => 3])
        ];
    }
}
