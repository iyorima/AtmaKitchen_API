<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PembayaranGaji>
 */
class PembayaranGajiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       
        return [
            'id_karyawan' => $this->faker->numberBetween(1, 10),
            'total' => $this->faker->randomFloat(2, 50000, 3000000),
            'bonus' => $this->faker->numberBetween(10000, 200000), 
        ];
    }
}
