<?php

namespace Database\Seeders;

use App\Models\ResepProduk;
use Illuminate\Database\Seeder;

class ResepProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 1,
            'jumlah' => 500
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 2,
            'jumlah' => 50
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 3,
            'jumlah' => 500
        ]);
    }
}
