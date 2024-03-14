<?php

namespace Database\Seeders;

use App\Models\Alamat;
use Illuminate\Database\Seeder;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i < 6; $i++) {
            Alamat::factory()->create([
                'id_pelanggan' => $i
            ]);
        }
    }
}
