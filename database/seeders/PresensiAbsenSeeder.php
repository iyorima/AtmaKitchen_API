<?php

namespace Database\Seeders;

use App\Models\PresensiAbsen;
use Illuminate\Database\Seeder;

class PresensiAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            PresensiAbsen::factory()->create([
                'id_karyawan' => $i
            ]);
        }
    }
}
