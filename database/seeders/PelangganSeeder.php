<?php

namespace Database\Seeders;

use App\Models\Pelanggan;
use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 3; $i++) {
            Pelanggan::factory()->create([
                'id_akun' => $i
            ]);
        }
    }
}
