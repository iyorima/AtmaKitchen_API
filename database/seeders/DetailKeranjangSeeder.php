<?php

namespace Database\Seeders;

use App\Models\DetailKeranjang;
use Illuminate\Database\Seeder;

class DetailKeranjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailKeranjang::factory()->count(50)->create();
    }
}
