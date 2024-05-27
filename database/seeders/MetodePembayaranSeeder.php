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
        // $metodePembayaran = [
        //     ['id_metode_pembayaran' => 'TRANSFER', 'nama' => 'Transfer'],
        //     ['id_metode_pembayaran' => 'CASH', 'nama' => 'Cash'],

        // ];

        foreach ($metodePembayaran as $item) {
            MetodePembayaran::create($item);
        }
    }
}
