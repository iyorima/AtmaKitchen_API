<?php

namespace Database\Seeders;

use App\Models\Alamat;
use Illuminate\Database\Seeder;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 6; $i++) {
            Alamat::factory()->create([
                'id_pelanggan' => $i
            ]);
        }
        Alamat::factory()->create([
            'id_pelanggan' => 1
        ]);
        Alamat::factory()->create([
            'id_pelanggan' => 1
        ]);
        for ($i = 1; $i < 10; $i++) {
            Alamat::factory()->create([
                'id_pelanggan' => fake()->numberBetween(1, 5)
            ]);
        }
    }
}
