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
        // status:
        // - menunggu ongkir *
        // - sudah dibayar
        // - pembayaran valid
        // - ditolak
        // - diterima
        // - diproses
        // - siap di-pickup
        // - sedang dikirim kurir
        // - sudah di-pickup
        // - selesai

        // 24.03.1 | Waiting state
        StatusPesanan::factory()->create([
            'id_pesanan' => '24.03.1',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '24.03.1',
            'id_karyawan' => 1,
            'status' => 'Sudah dibayar'
        ]);

        // 24.03.2 | Success state
        StatusPesanan::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_karyawan' => 1,
            'status' => 'Menunggu ongkir'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_karyawan' => 1,
            'status' => 'Sudah dibayar'
        ]);
        StatusPesanan::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_karyawan' => 1,
            'status' => 'Pembayaran valid'
        ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.2',
        //     'id_karyawan' => 1,
        //     'status' => 'Diterima'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.2',
        //     'id_karyawan' => 1,
        //     'status' => 'Diproses'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.2',
        //     'id_karyawan' => 1,
        //     'status' => 'Sedang dikirim kurir'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.2',
        //     'id_karyawan' => 1,
        //     'status' => 'Selesai'
        // ]);

        // 24.03.3 | Rejected State
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Sudah dibayar'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Pembayaran valid'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Diterima'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Diproses'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Siap dipickup'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Sudah dipickup'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.3',
        //     'id_karyawan' => 1,
        //     'status' => 'Selesai'
        // ]);

        // // 24.03.4 | Menunggu ongkir
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.4',
        //     'id_karyawan' => 1,
        //     'status' => 'Menunggu ongkir'
        // ]);
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

        // 24.03.5 | Ditolak
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.5',
        //     'id_karyawan' => 1,
        //     'status' => 'Menunggu ongkir'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.5',
        //     'id_karyawan' => 1,
        //     'status' => 'Sudah dibayar'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.5',
        //     'id_karyawan' => 1,
        //     'status' => 'Pembayaran valid'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.5',
        //     'id_karyawan' => 1,
        //     'status' => 'Ditolak'
        // ]);

        // // 24.03.6 | Waiting kurir state
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Menunggu ongkir'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Sudah dibayar'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Pembayaran valid'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Diterima'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Diproses'
        // ]);
        // StatusPesanan::factory()->create([
        //     'id_pesanan' => '24.03.6',
        //     'id_karyawan' => 1,
        //     'status' => 'Sedang dikirim kurir'
        // ]);
    }
}
