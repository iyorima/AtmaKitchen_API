<?php

namespace Database\Seeders;

use App\Models\PemesananBahanBaku;
use Illuminate\Database\Seeder;

class PemesananBahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PemesananBahanBaku::factory()->count(10)->create();
    }
}
