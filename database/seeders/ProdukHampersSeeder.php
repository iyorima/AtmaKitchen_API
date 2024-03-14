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
            'id_produk' => 20,
            'nama' => 'Lapis Legit',
            'satuan' => '1/2 Loyang',
            'jumlah' => 1,
        ]);
        ProdukHampers::factory()->create([
            'id_produk' => 20,
            'nama' => 'Brownies',
            'satuan' => '1/2 Loyang',
            'jumlah' => 1,
        ]);
        ProdukHampers::factory()->create([
            'id_produk' => 21,
            'nama' => 'Lapis Surabaya',
            'satuan' => '1/2 Loyang',
            'jumlah' => 1,
        ]);
        ProdukHampers::factory()->create([
            'id_produk' => 21,
            'nama' => 'Roti Sosis',
            'satuan' => 'Pcs',
            'jumlah' => 1,
        ]);
        ProdukHampers::factory()->create([
            'id_produk' => 22,
            'nama' => 'Spikoe',
            'satuan' => '1/2 Loyang',
            'jumlah' => 1,
        ]);
        ProdukHampers::factory()->create([
            'id_produk' => 22,
            'nama' => 'Matcha Creamy Latte',
            'satuan' => 'Pcs',
            'jumlah' => 1,
        ]);
    }
}
