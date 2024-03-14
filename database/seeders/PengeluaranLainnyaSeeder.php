<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengeluaranLainnya;
class PengeluaranLainnyaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PengeluaranLainnya::factory()->count(10)->create();
    }
}
