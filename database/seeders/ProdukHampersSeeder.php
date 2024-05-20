<?php

namespace Database\Seeders;

use App\Models\ProdukHampers;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukHampersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProdukHampers::factory()->create([
            'nama' => 'Paket A',
            'harga_jual' => '650000',
            'image' => 'https://atmaimages.blob.core.windows.net/images/PAKET A.png'
        ]);
        ProdukHampers::factory()->create([
            'nama' => 'Paket B',
            'harga_jual' => '50000',
            'image' => 'https://atmaimages.blob.core.windows.net/images/PAKET B.png'
        ]);
        ProdukHampers::factory()->create([
            'nama' => 'Paket C',
            'harga_jual' => '350000',
            'image' => 'https://atmaimages.blob.core.windows.net/images/PAKET C.png'
        ]);
    }
}
