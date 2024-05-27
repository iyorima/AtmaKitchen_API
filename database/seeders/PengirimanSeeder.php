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
            'id_pesanan' => '24.03.1',
            // 'id_kategori_pengiriman' => 4,
            'jarak' => 27,
            'harga' => 25000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '24.03.2',
            // 'id_kategori_pengiriman' => 3,
            'jarak' => 11,
            'harga' => 20000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '24.03.4',
            // 'id_kategori_pengiriman' => 4,
            // 'id_kurir' => 10,
            // 'jarak' => 27,
            // 'harga' => 25000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '24.03.5',
            // 'id_kategori_pengiriman' => 1,
            // 'id_kurir' => 1,
            // 'jarak' => 1,
            // 'harga' => 10000
        ]);

        Pengiriman::factory()->create([
            'id_pesanan' => '24.03.6',
            // 'id_kategori_pengiriman' => 1,
            'jarak' => 1,
            'harga' => 10000
        ]);
    }
}
