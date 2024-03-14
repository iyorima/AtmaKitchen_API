<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePembayaran;
class MetodePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metodePembayaran = [
            ['id_metode_pembayaran' => 1, 'nama' => 'Transfer'],
            ['id_metode_pembayaran' => 2, 'nama' => 'Cash'],
           
        ];

        foreach ($metodePembayaran as $item) {
            MetodePembayaran::create($item);
        }
    }
}
