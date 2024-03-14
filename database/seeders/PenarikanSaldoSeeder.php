<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenarikanSaldo;

class PenarikanSaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PenarikanSaldo::factory()->count(10)->create();
    }
}
