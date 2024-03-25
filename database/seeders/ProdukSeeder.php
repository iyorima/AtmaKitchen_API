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
            'nama' => 'Lapis Legit',
            'ukuran' => '20x20',
            'harga_jual' => 850000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Legit',
            'ukuran' => '10x20',
            'harga_jual' => 450000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Surabaya',
            'ukuran' => '20x20',
            'harga_jual' => 550000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Lapis Surabaya',
            'ukuran' => '10x20',
            'harga_jual' => 300000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Brownies',
            'ukuran' => '20x20',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Brownies',
            'ukuran' => '10x20',
            'harga_jual' => 150000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Mandarin',
            'ukuran' => '20x20',
            'harga_jual' => 450000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Mandarin',
            'ukuran' => '10x20',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Spikoe',
            'ukuran' => '20x20',
            'harga_jual' => 350000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Spikoe',
            'ukuran' => '10x20',
            'harga_jual' => 200000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Roti Sosis',
            'ukuran' => 'isi 10',
            'harga_jual' => 180000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Milk Bun',
            'ukuran' => 'isi 10',
            'harga_jual' => 120000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Roti Keju',
            'ukuran' => 'isi 10',
            'harga_jual' => 150000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Choco Creamy Latte',
            'ukuran' => '1 Liter',
            'harga_jual' => 75000
        ]);
        Produk::factory()->create([
            'id_kategori' => 1,
            'nama' => 'Matcha Creamy Latte',
            'ukuran' => '1 Liter',
            'harga_jual' => 100000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Keripik Kentang',
            'ukuran' => '250 gr',
            'harga_jual' => 75000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Kopi Luwak Bubuk',
            'ukuran' => '250 gr',
            'harga_jual' => 250000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-02',
            'nama' => 'Matcha Organik Bubuk',
            'ukuran' => '100 gr',
            'harga_jual' => 300000
        ]);
        Produk::factory()->create([
            'id_kategori' => 3,
            'id_penitip' => 'Penitip-01',
            'nama' => 'Chocolate Bar',
            'ukuran' => '100 gr',
            'harga_jual' => 120000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket A',
            'ukuran' => '',
            'harga_jual' => 650000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket B',
            'ukuran' => '',
            'harga_jual' => 500000
        ]);
        Produk::factory()->create([
            'id_kategori' => 2,
            'nama' => 'Hampers Paket C',
            'ukuran' => '',
            'harga_jual' => 350000
        ]);
    }
}
