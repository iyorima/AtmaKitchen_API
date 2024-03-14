<?php

namespace Database\Seeders;

use App\Models\Penitip;
use Illuminate\Database\Seeder;

class PenitipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Penitip::factory()->create([
            'id_penitip' => 'penitip-01'
        ]);
        Penitip::factory()->create([
            'id_penitip' => 'penitip-02'
        ]);
        Penitip::factory()->create([
            'id_penitip' => 'penitip-03'
        ]);
        Penitip::factory()->create([
            'id_penitip' => 'penitip-04'
        ]);
        Penitip::factory()->create([
            'id_penitip' => 'penitip-05'
        ]);
    }
}
