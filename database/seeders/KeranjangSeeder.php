<?php

namespace Database\Seeders;

use App\Models\Keranjang;
use Illuminate\Database\Seeder;

class KeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Keranjang::factory()->create([
            'id_pelanggan' => 1
        ]);
        Keranjang::factory()->create([
            'id_pelanggan' => 2
        ]);
        // Keranjang::factory()->create([
        //     'id_pelanggan' => 1
        // ]);
        // Keranjang::factory()->create([
        //     'id_pelanggan' => 1
        // ]);
        // Keranjang::factory()->create([
        //     'id_pelanggan' => 2
        // ]);
        // Keranjang::factory()->count(5)->create();
    }
}
