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
        Karyawan::factory()->create([
            'id_akun' => 3,
        ]);
        Karyawan::factory()->count(10)->create();
    }
}
