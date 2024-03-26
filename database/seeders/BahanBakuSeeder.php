<?php

namespace Database\Seeders;

use App\Enum\SatuanEnum;
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
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Creamer',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Telur',
            'satuan' => SatuanEnum::BUTIR,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Gula Pasir',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Bubuk',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tepung Terigu',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tepung Maizena',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Garam',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Coklat Bubuk',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Selai Strawberry',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Coklat Batang',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Minyak Goreng',
            'satuan' => SatuanEnum::ML,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Baking Powder',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kacang Kenari',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Ragi',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kuning Telur',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Cair',
            'satuan' => SatuanEnum::ML,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Sosis Blackpapper',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Whipped Cream',
            'satuan' => SatuanEnum::ML,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Susu Full Cream',
            'satuan' => SatuanEnum::ML,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Keju Mozzarella',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Matcha Bubuk',
            'satuan' => SatuanEnum::GRAM,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box 20x20 cm',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box 20x10 cm',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Botol 1 Liter',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Box Premium',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Kartu Ucapan',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Tas Spunbond',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Exclusive Box',
            'satuan' => SatuanEnum::BUAH,
        ]);

        BahanBaku::factory()->create([
            'nama' => 'Card',
            'satuan' => SatuanEnum::BUAH,
        ]);
    }
}
