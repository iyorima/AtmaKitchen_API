<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetodePembayaran>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->randomKey(['Customer' => 1, 'Owner' => 2, 'Admin' => 3, 'Manager Operasional' => 4, 'Driver' => 5, 'Penitip' => 6])
        ];
    }
}
