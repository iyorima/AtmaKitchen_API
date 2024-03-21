<?php

namespace Database\Seeders;

use App\Models\Pengiriman;
use Illuminate\Database\Seeder;

class PengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_kategori_pengiriman' => 4,
            'id_kurir' => 10,
            'jarak' => 27,
            'harga' => 25000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_kategori_pengiriman' => 3,
            'id_kurir' => 10,
            'jarak' => 11,
            'harga' => 20000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_kategori_pengiriman' => 4,
            'id_kurir' => 10,
            'jarak' => 27,
            'harga' => 25000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_kategori_pengiriman' => 1,
            'id_kurir' => 1,
            'jarak' => 1,
            'harga' => 10000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_kategori_pengiriman' => 1,
            'id_kurir' => 1,
            'jarak' => 1,
            'harga' => 10000
        ]);
    }
}
