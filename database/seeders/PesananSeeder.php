<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_pelanggan' => 1,
            'total_diskon_poin' => 0,
            'total_pesanan' => 450000, // Mandarin
            'total_setelah_diskon' => 450000
        ]);

        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_pelanggan' => 2,
            'total_diskon_poin' => 0,
            'total_pesanan' => 120000, // Milk Bun
            'total_setelah_diskon' => 120000
        ]);

        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_pelanggan' => 3,
            'total_diskon_poin' => 0,
            'total_pesanan' => 850000, // Lapis legit
            'total_setelah_diskon' => 850000
        ]);

        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.004',
            'id_pelanggan' => 4,
            'total_diskon_poin' => 0,
            'total_pesanan' => 75000, // Keripik kentang
            'total_setelah_diskon' => 75000
        ]);

        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_pelanggan' => 5,
            'total_diskon_poin' => 0,
            'total_pesanan' => 120000, // Chocolate Bar 100gr
            'total_setelah_diskon' => 120000
        ]);

        Pesanan::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_pelanggan' => 1,
            'total_diskon_poin' => 6000,
            'total_pesanan' => 270000, // Chocolate Bar 100gr + Keripik kentang (2)
            'total_setelah_diskon' => 264000
        ]);
    }
}
