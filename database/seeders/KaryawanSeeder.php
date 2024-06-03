<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Karyawan;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Karyawan::factory()->create([
        //     'id_akun' => 3,
        // ]);

        for ($i = 3; $i < 6; $i++) {
            Karyawan::factory()->create([
                'id_akun' => $i,
            ]);
        }
    }
}
