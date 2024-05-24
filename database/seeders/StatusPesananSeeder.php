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
        // 07.03.24.001 | Waiting state
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.001',
            'id_karyawan' => 1,
            'status' => 'Menunggu pembayaran'
        ]);

        // 17.03.24.002 | Success state
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Menunggu pembayaran'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Menunggu konfirmasi pembayaran'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Pesanan diproses'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Pesanan dikirim'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.002',
            'id_karyawan' => 1,
            'status' => 'Pesanan diterima'
        ]);

        // 17.03.24.003 | Rejected State
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '17.03.24.003',
        //     'id_karyawan' => 1,
        //     'status' => 'Menunggu ongkir'
        // ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_karyawan' => 1,
            'status' => 'Menunggu pembayaran'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_karyawan' => 1,
            'status' => 'Menunggu konfirmasi pembayaran'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_karyawan' => 1,
            'status' => 'Pesanan diproses'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.003',
            'id_karyawan' => 1,
            'status' => 'Pesanan ditolak'
        ]);

        // 17.03.24.004 | Success
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.004',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '17.03.24.004',
        //     'id_karyawan' => 1,
        //     'status' => 'Menunggu konfirmasi pembayaran'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '17.03.24.004',
        //     'id_karyawan' => 1,
        //     'status' => 'Pesanan diproses'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '17.03.24.004',
        //     'id_karyawan' => 1,
        //     'status' => 'Pesanan siap diambil'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '17.03.24.004',
        //     'id_karyawan' => 1,
        //     'status' => 'Pesanan telah diambil'
        // ]);

        // 17.03.24.005 | Success
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.005',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);

        // 17.03.24.006 | Waiting state
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '17.03.24.006',
            'id_karyawan' => 1,
            'status' => 'Menunggu pembayaran'
        ]);
    }
}
