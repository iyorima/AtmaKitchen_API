<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenarikanSaldo>
 */
class PenarikanSaldoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_akun' => $this->faker->numberBetween(1, 10),
            'jumlah_penarikan' => $this->faker->randomFloat(2, 100, 500000),
            'transfer_at' => $this->faker->dateTimeThisMonth(),
        ];
    }
}
