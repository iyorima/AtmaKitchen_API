<?php

namespace Database\Seeders;

use App\Models\SaldoPelanggan;
use Illuminate\Database\Seeder;

class SaldoPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaldoPelanggan::factory()->create([
            'id_akun' => 4,
            'id_pesanan' => '17.03.24.004',
            'saldo' => 75000,
            'total_saldo' => 75000
        ]);
    }
}
