<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriPengiriman>
 */
class KategoriPengirimanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jarak_minimum' => $this->faker->randomNumber(2), 
            'harga' => $this->faker->numberBetween(10000, 50000),
        ];
    }
}
