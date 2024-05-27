<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailKeranjang>
 */
class DetailKeranjangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_keranjang' => fake()->numberBetween(1, 2),
            // 'id_produk' => fake()->randomNumber(1, 19),
            'jumlah' => fake()->numberBetween(1, 3)
        ];
    }
}
