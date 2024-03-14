<?php

namespace Database\Seeders;

use App\Models\KategoriPengiriman;
use Illuminate\Database\Seeder;

class KategoriPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriPengiriman::factory()->count(10)->create();
    }
}
