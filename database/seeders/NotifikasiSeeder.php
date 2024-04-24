<?php

namespace Database\Seeders;

use App\Models\Notifikasi;
use Illuminate\Database\Seeder;

class NotifikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Notifikasi::factory()->create([
            'judul' => "Diskon",
            'deskripsi' => "Kami akan segera mengabari saat pesananmu tiba",
            'id_akun' => 1
        ]);
        Notifikasi::factory()->create([
            'judul' => "Potongan 50% untuk Spikoe 20x20cm",
            'deskripsi' => "Dapatkan potongan 50% untuk Spikoe 20x20cm hanya hari ini",
            'id_akun' => null
        ]);
        // for ($i = 1; $i < 6; $i++) {
        //     Notifikasi::factory()->create([
        //         'judul' => fake()->sentences(),
        //         'deskripsi' => fake()->sentences(),
        //         'id_akun' => null
        //     ]);
        // }
    }
}
