<?php

namespace Database\Seeders;

use App\Models\DetailHampers;
use Illuminate\Database\Seeder;

class DetailHampersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailHampers::factory()->create([
            'id_produk_hampers' => 1,
            'id_produk' => 2
        ]);
        DetailHampers::factory()->create([
            'id_produk_hampers' => 1,
            'id_produk' => 6
        ]);
        DetailHampers::factory()->create([
            'id_produk_hampers' => 2,
            'id_produk' => 4
        ]);
        DetailHampers::factory()->create([
            'id_produk_hampers' => 2,
            'id_produk' => 11
        ]);
        DetailHampers::factory()->create([
            'id_produk_hampers' => 3,
            'id_produk' => 10
        ]);
        DetailHampers::factory()->create([
            'id_produk_hampers' => 3,
            'id_produk' => 15
        ]);
    }
}
