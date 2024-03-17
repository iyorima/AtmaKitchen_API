<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use Illuminate\Database\Seeder;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // TODO: Buat apa ya ini? titipan bisa di cek dari produk kan ada id_penitip :(
        KategoriProduk::factory()->create([
            'kategori' => 'Cake'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Roti'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Minuman'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Titipan'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Hampers'
        ]);
    }
}
