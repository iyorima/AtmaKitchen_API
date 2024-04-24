<?php

namespace Database\Seeders;

use App\Models\ProdukImage;
use Illuminate\Database\Seeder;

class ProdukImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProdukImage::factory()->count(50)->create();
    }
}
