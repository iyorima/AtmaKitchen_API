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
            'id_pesanan' => '17.03.24.00001',
            'id_kategori_pengiriman' => 1,
            'id_kurir' => 10,
            'jarak' => 27,
            'harga' => 44327
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.00002',
            'id_kategori_pengiriman' => 2,
            'id_kurir' => 10,
            'jarak' => 10,
            'harga' => 42712
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.00003',
            'id_kategori_pengiriman' => 2,
            'id_kurir' => 10,
            'jarak' => 27,
            'harga' => 44327
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '17.03.24.00005',
            'id_kategori_pengiriman' => 4,
            'id_kurir' => 1,
            'jarak' => 1,
            'harga' => 10323
        ]);
    }
}
