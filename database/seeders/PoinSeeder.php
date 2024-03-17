<?php

namespace Database\Seeders;

use App\Models\Poin;
use Illuminate\Database\Seeder;

class PoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Poin::factory()->create([
            'id_pesanan' => '17.03.24.00001',
            'id_pelanggan' => 1,
            'penambahan_poin' => 65,
            'total_poin' => 65
        ]);

        Poin::factory()->create([
            'id_pesanan' => '17.03.24.00002',
            'id_pelanggan' => 2,
            'penambahan_poin' => 17,
            'total_poin' => 17
        ]);

        Poin::factory()->create([
            'id_pesanan' => '17.03.24.00003',
            'id_pelanggan' => 3,
            'penambahan_poin' => 125,
            'total_poin' => 125
        ]);

        Poin::factory()->create([
            'id_pesanan' => '17.03.24.00004',
            'id_pelanggan' => 4,
            'penambahan_poin' => 7,
            'total_poin' => 7
        ]);

        Poin::factory()->create([
            'id_pesanan' => '17.03.24.00005',
            'id_pelanggan' => 5,
            'penambahan_poin' => 45,
            'total_poin' => 45
        ]);
    }
}
