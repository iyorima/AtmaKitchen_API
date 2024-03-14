<?php

namespace Database\Seeders;

use App\Models\BahanBaku;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BahanBaku::factory()->create([
            'nama' => 'Butter',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Creamer',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Telur',
            'satuan' => 'butir',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Gula Pasir',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Bubuk',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tepung Terigu',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tepung Maizena',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Garam',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Coklat Bubuk',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Selai Strawberry',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Coklat Batang',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Minyak Goreng',
            'satuan' => 'ml',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Baking Powder',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kacang Kenari',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Ragi',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kuning Telur',
            'satuan' => 'buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Cair',
            'satuan' => 'ml',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Sosis Blackpapper',
            'satuan' => 'buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Whipped Cream',
            'satuan' => 'ml',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Full Cream',
            'satuan' => 'ml',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Keju Mozzarella',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Matcha Bubuk',
            'satuan' => 'gr',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box 20x20 cm',
            'satuan' => 'Buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box 20x10 cm',
            'satuan' => 'Buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Botol 1 Liter',
            'satuan' => 'Buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box Premium',
            'satuan' => 'Buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kartu Ucapan',
            'satuan' => 'Buah',
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tas Spunbond',
            'satuan' => 'Buah',
        ]);
    }
}
