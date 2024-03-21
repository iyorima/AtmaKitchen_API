<?php

namespace Database\Seeders;

use App\Models\StatusPesanan;
use Illuminate\Database\Seeder;

class StatusPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_karyawan' => 1,
            'status' => 'selesai'
        ]);

        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'selesai'
        ]);

        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_karyawan' => 3,
            'status' => 'selesai'
        ]);

        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.004',
            'id_karyawan' => 3,
            'status' => 'ditolak'
        ]);

        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_karyawan' => 3,
            'status' => 'selesai'
        ]);
    }
}
