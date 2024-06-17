<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Karyawan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PengeluaranLainnya>
 */
class PengeluaranLainnyaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $karyawanIds = Karyawan::pluck('id_karyawan')->toArray();
        return [
            'id_karyawan' => $this->faker->numberBetween(1, 3),
            // 'nama' => Karyawan::find($this->faker->randomElement($karyawanIds))->nama,
            'nama' => $this->faker->randomKey(['Listrik' => 1, 'Iuran RT' => 2, 'Bensin' => 3, 'Gas' => 4]),
            'biaya' => $this->faker->randomFloat(2, 500, 2000000),
            'tanggal' => $this->faker->dateTimeThisMonth(),
            'kategori' => "Pengeluaran"
        ];
    }
}
