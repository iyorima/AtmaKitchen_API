<?php

namespace Database\Seeders;

use App\Models\PembayaranGaji;
use Illuminate\Database\Seeder;

class PembayaranGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PembayaranGaji::factory()->count(10)->create();
    }
}
