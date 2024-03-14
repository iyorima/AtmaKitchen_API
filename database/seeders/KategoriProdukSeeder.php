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
            'kategori' => 'Pre-Order'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Ready Stock'
        ]);
        KategoriProduk::factory()->create([
            'kategori' => 'Titipan'
        ]);
    }
}
