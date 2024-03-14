<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Legit 20x20',
            'harga_jual' => 850000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Legit 10x20',
            'harga_jual' => 450000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Surabaya 20x20',
            'harga_jual' => 550000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Surabaya 10x20',
            'harga_jual' => 300000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Brownies 20x20',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Brownies 10x20',
            'harga_jual' => 150000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Mandarin 20x20',
            'harga_jual' => 450000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Mandarin 10x20',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Spikoe 20x20',
            'harga_jual' => 350000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Spikoe 10x20',
            'harga_jual' => 200000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Roti Sosis isi 10',
            'harga_jual' => 180000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Milk Bun isi 10',
            'harga_jual' => 120000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Roti Keju isi 10',
            'harga_jual' => 150000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Choco Creamy Latte (1L)',
            'harga_jual' => 75000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Matcha Creamy Latte (1L)',
            'harga_jual' => 100000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Keripik Kentang 250gr',
            'harga_jual' => 75000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Kopi Luwak Bubuk 250gr',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-02',
            'nama' => 'Matcha Organik Bubuk 100gr',
            'harga_jual' => 300000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Chocolate Bar 100gr',
            'harga_jual' => 120000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket A',
            'harga_jual' => 650000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket B',
            'harga_jual' => 500000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket C',
            'harga_jual' => 350000
        ]);
    }
}
