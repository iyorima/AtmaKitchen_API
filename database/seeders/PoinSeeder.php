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
            'id_pesanan' => '24.03.1',
            'id_pelanggan' => 2,
            'penambahan_poin' => 65,
            'total_poin' => 65
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_pelanggan' => 2,
            'penambahan_poin' => 17,
            'total_poin' => 17
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.3',
            'id_pelanggan' => 2,
            'penambahan_poin' => 125,
            'total_poin' => 125
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.4',
            'id_pelanggan' => 2,
            'penambahan_poin' => 7,
            'total_poin' => 7
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.5',
            'id_pelanggan' => 2,
            'penambahan_poin' => 45,
            'total_poin' => 45
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.6',
            'id_pelanggan' => 2,
            'penambahan_poin' => -60,
            'total_poin' => 5
        ]);

        Poin::factory()->create([
            'id_pesanan' => '24.03.6',
            'id_pelanggan' => 2,
            'penambahan_poin' => 45,
            'total_poin' => 50
        ]);
    }
}
