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
        KategoriPengiriman::factory()->create([
            'jarak_minimum' => 0,
            'harga' => 10000
        ]);
        KategoriPengiriman::factory()->create([
            'jarak_minimum' => 5,
            'harga' => 15000
        ]);
        KategoriPengiriman::factory()->create([
            'jarak_minimum' => 10,
            'harga' => 20000
        ]);
        KategoriPengiriman::factory()->create([
            'jarak_minimum' => 15,
            'harga' => 25000
        ]);
    }
}
