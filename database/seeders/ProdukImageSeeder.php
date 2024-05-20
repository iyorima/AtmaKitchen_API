<?php

namespace Database\Seeders;

use App\Models\ProdukImage;
use Illuminate\Database\Seeder;

class ProdukImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProdukImage::factory()->create([
            "id_produk" => 1,
            "image" => "https://atmaimages.blob.core.windows.net/images/Lapis legit.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 2,
            "image" => "https://atmaimages.blob.core.windows.net/images/Lapis legit.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 3,
            "image" => "https://atmaimages.blob.core.windows.net/images/Lapis surabaya.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 4,
            "image" => "https://atmaimages.blob.core.windows.net/images/Lapis surabaya.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 5,
            "image" => "https://atmaimages.blob.core.windows.net/images/Brownies.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 6,
            "image" => "https://atmaimages.blob.core.windows.net/images/Brownies.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 7,
            "image" => "https://atmaimages.blob.core.windows.net/images/Mandarin.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 8,
            "image" => "https://atmaimages.blob.core.windows.net/images/Mandarin.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 9,
            "image" => "https://atmaimages.blob.core.windows.net/images/Spikoe.PNG"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 10,
            "image" => "https://atmaimages.blob.core.windows.net/images/Spikoe.PNG"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 11,
            "image" => "https://atmaimages.blob.core.windows.net/images/Roti sosis.PNG"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 12,
            "image" => "https://atmaimages.blob.core.windows.net/images/Milk bun.PNG"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 13,
            "image" => "https://atmaimages.blob.core.windows.net/images/Roti keju.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 14,
            "image" => "https://atmaimages.blob.core.windows.net/images/Choco creamy latte.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 15,
            "image" => "https://atmaimages.blob.core.windows.net/images/Matcha creamy latte.PNG"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 16,
            "image" => "https://atmaimages.blob.core.windows.net/images/Keripik kentang.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 17,
            "image" => "https://atmaimages.blob.core.windows.net/images/Kopi luwak bubuk.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 18,
            "image" => "https://atmaimages.blob.core.windows.net/images/Matcha organik bubuk.png"
        ]);
        ProdukImage::factory()->create([
            "id_produk" => 19,
            "image" => "https://atmaimages.blob.core.windows.net/images/Chocolate bar.png"
        ]);
        ProdukImage::factory()->count(20)->create();
    }
}
