<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use Illuminate\Database\Seeder;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_produk' => 7,
            'kategori' => 'Cake',
            'nama_produk' => 'Mandarin 20x20',
            'harga' => 450000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_produk' => 12,
            'kategori' => 'Roti',
            'nama_produk' => 'Milk Bun isi 10',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_produk' => 1,
            'kategori' => 'Cake',
            'nama_produk' => 'Lapis Legit 20x20',
            'harga' => 850000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.004',
            'id_produk' => 16,
            'kategori' => 'Titipan',
            'nama_produk' => 'Keripik Kentang 250gr',
            'harga' => 75000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_produk' => 19,
            'kategori' => 'Titipan',
            'nama_produk' => 'Chocolate Bar 100gr',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_produk' => 19,
            'kategori' => 'Titipan',
            'nama_produk' => 'Chocolate Bar 100gr',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_produk' => 16,
            'kategori' => 'Titipan',
            'nama_produk' => 'Keripik Kentang 250gr',
            'harga' => 75000,
            'jumlah' => 2
        ]);
    }
}