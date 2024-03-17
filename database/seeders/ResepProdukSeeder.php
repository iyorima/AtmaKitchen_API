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
        // Lapis Legit
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

        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 4,
            'jumlah' => 300
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 5,
            'jumlah' => 100
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 1,
            'id_bahan_baku' => 6,
            'jumlah' => 20
        ]);

        // Lapis Surabaya
        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 1,
            'jumlah' => 500
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 2,
            'jumlah' => 50
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 3,
            'jumlah' => 40
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 4,
            'jumlah' => 300
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 6,
            'jumlah' => 100
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 5,
            'jumlah' => 100
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 8,
            'jumlah' => 10
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 9,
            'jumlah' => 25
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 3,
            'id_bahan_baku' => 10,
            'jumlah' => 100
        ]);

        // Brownies
        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 11,
            'jumlah' => 250
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 1,
            'jumlah' => 100
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 12,
            'jumlah' => 50
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 3,
            'jumlah' => 6
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 4,
            'jumlah' => 200
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 6,
            'jumlah' => 150
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 5,
            'id_bahan_baku' => 9,
            'jumlah' => 60
        ]);

        // Mandarin
        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 1,
            'jumlah' => 300
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 2,
            'jumlah' => 30
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 3,
            'jumlah' => 30
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 4,
            'jumlah' => 200
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 6,
            'jumlah' => 80
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 5,
            'jumlah' => 80
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 8,
            'jumlah' => 5
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 9,
            'jumlah' => 25
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 7,
            'id_bahan_baku' => 10,
            'jumlah' => 50
        ]);

        // Spikoe
        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 3,
            'jumlah' => 20
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 4,
            'jumlah' => 200
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 6,
            'jumlah' => 90
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 7,
            'jumlah' => 20
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 5,
            'jumlah' => 10
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 13,
            'jumlah' => 5
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 1,
            'jumlah' => 200
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 9,
            'id_bahan_baku' => 14,
            'jumlah' => 100
        ]);

        // Roti Sosis
        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 6,
            'jumlah' => 250
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 4,
            'jumlah' => 30
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 15,
            'jumlah' => 3
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 16,
            'jumlah' => 3
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 17,
            'jumlah' => 150
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 1,
            'jumlah' => 50
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 8,
            'jumlah' => 2
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 11,
            'id_bahan_baku' => 18,
            'jumlah' => 10
        ]);

        // Milk Bun
        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 6,
            'jumlah' => 250
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 4,
            'jumlah' => 30
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 15,
            'jumlah' => 3
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 16,
            'jumlah' => 4
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 17,
            'jumlah' => 300
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 1,
            'jumlah' => 60
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 8,
            'jumlah' => 3
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 19,
            'jumlah' => 200
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 12,
            'id_bahan_baku' => 5,
            'jumlah' => 50
        ]);

        // Roti Keju
        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 6,
            'jumlah' => 250
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 4,
            'jumlah' => 30
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 15,
            'jumlah' => 3
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 20,
            'jumlah' => 150
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 1,
            'jumlah' => 50
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 8,
            'jumlah' => 2
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 13,
            'id_bahan_baku' => 21,
            'jumlah' => 350
        ]);

        // Choco Creamy Latte
        ResepProduk::factory()->create([
            'id_produk' => 14,
            'id_bahan_baku' => 9,
            'jumlah' => 120
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 14,
            'id_bahan_baku' => 2,
            'jumlah' => 80
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 14,
            'id_bahan_baku' => 17,
            'jumlah' => 800
        ]);

        // Matcha Creamy Latte
        ResepProduk::factory()->create([
            'id_produk' => 15,
            'id_bahan_baku' => 22,
            'jumlah' => 120
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 15,
            'id_bahan_baku' => 2,
            'jumlah' => 80
        ]);

        ResepProduk::factory()->create([
            'id_produk' => 15,
            'id_bahan_baku' => 17,
            'jumlah' => 800
        ]);
    }
}
