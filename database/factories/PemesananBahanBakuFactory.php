<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BahanBaku;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PemesananBahanBaku>
 */
class PemesananBahanBakuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bahanBakuIds = BahanBaku::pluck('id_bahan_baku')->toArray();

        return [
            'id_bahan_baku' => $this->faker->numberBetween(1, 10),
            'nama' => BahanBaku::find($this->faker->randomElement($bahanBakuIds))->nama,
            'satuan' => $this->faker->randomElement(['gram', 'butir', 'buah', 'ml']),
            'jumlah' => $this->faker->randomNumber(2, false),
            'harga_beli' => $this->faker->randomFloat(2, 500, 100000),
            'total' => $this->faker->randomFloat(2, 500, 2000000),
        ];
    }
}
