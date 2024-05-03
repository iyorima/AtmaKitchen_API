<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BahanBaku>
 */
class BahanBakuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'stok' => $this->faker->numberBetween(1, 10000),
            'stok_minumum' => $this->faker->numberBetween(1, 10000),
            'updated_at' => $this->faker->dateTimeThisMonth()->format('Y-m-d H:i:s'),
        ];
    }
}
