<?php

namespace Database\Seeders;

use App\Models\PresensiAbsen;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PresensiAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $randomDate = Carbon::createFromTimestamp(mt_rand(strtotime('first day of last month'), strtotime('today')));
            PresensiAbsen::factory()->create([
                'id_karyawan' => fake()->numberBetween(1, 3),
                'tanggal' => $randomDate
            ]);
        }
    }
}
