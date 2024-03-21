<?php

namespace Database\Seeders;

use App\Models\Pembayaran;
use Illuminate\Database\Seeder;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_metode_pembayaran' => 1,
            'total_dibayarkan' => 450000,
            'total_tip' => 0
        ]);

        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_metode_pembayaran' => 2,
            'total_dibayarkan' => 120000,
            'total_tip' => 30000
        ]);

        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_metode_pembayaran' => 1,
            'total_dibayarkan' => 850000,
            'total_tip' => 0
        ]);

        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.004',
            'id_metode_pembayaran' => 1,
            'total_dibayarkan' => 75000,
            'total_tip' => 5000
        ]);

        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_metode_pembayaran' => 1,
            'total_dibayarkan' => 300000,
            'total_tip' => 0
        ]);

        Pembayaran::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_metode_pembayaran' => 1,
            'total_dibayarkan' => 295000,
            'total_tip' => 1000
        ]);
    }
}
