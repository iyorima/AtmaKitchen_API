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
            'id_pesanan' => '24.03.1',
            'id_produk' => 7,
            'kategori' => 'Cake',
            'nama_produk' => 'Mandarin',
            'harga' => 450000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.1',
            'id_produk' => 12,
            'kategori' => 'Roti',
            'nama_produk' => 'Milk Bun',
            'harga' => 120000,
            'jumlah' => 2
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.1',
            'id_produk' => 1,
            'kategori' => 'Cake',
            'nama_produk' => 'Lapis Legit',
            'harga' => 850000,
            'jumlah' => 5
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_produk' => 12,
            'kategori' => 'Roti',
            'nama_produk' => 'Milk Bun',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.3',
            'id_produk' => 1,
            'kategori' => 'Cake',
            'nama_produk' => 'Lapis Legit',
            'harga' => 850000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.4',
            'id_produk' => 16,
            'kategori' => 'Titipan',
            'nama_produk' => 'Keripik Kentang',
            'harga' => 75000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.5',
            'id_produk' => 19,
            'kategori' => 'Titipan',
            'nama_produk' => 'Chocolate Bar',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.6',
            'id_produk' => 19,
            'kategori' => 'Titipan',
            'nama_produk' => 'Chocolate Bar',
            'harga' => 120000,
            'jumlah' => 1
        ]);

        DetailPesanan::factory()->create([
            'id_pesanan' => '24.03.6',
            'id_produk' => 16,
            'kategori' => 'Titipan',
            'nama_produk' => 'Keripik Kentang',
            'harga' => 75000,
            'jumlah' => 2
        ]);
    }
}
