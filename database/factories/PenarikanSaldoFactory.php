<?php

namespace Database\Factories;

use App\Models\PenarikanSaldo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenarikanSaldoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PenarikanSaldo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_akun' => $this->faker->numberBetween(3, 5),
            'jumlah_penarikan' => $this->faker->randomFloat(2, 100, 500000),
            'status' => 'menunggu',
            'transfer_at' => $this->faker->dateTimeThisMonth(),
            'nama_bank' => $this->faker->randomElement(['bca', 'mandiri']),
            'nomor_rekening' => $this->faker->bankAccountNumber,
        ];
    }
}
