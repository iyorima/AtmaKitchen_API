<?php

namespace Database\Seeders;

use App\Models\Pesanan;
use Illuminate\Database\Seeder;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pesanan diterima tapi belum diverifikasi pembayarannya
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.1',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 1,
            'total_diskon_poin' => 0,
            'total_pesanan' => 4940000, // Mandarin
            'total_setelah_diskon' => 4940000,
            'total_dibayarkan' => null,
            'total_tip' => 0,
            'jenis_pengiriman' => 'Kurir Toko'
        ]);

        // Pesanan selesai
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.2',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 1,
            'total_diskon_poin' => 0,
            'total_pesanan' => 120000, // Milk Bun
            'total_setelah_diskon' => 120000,
            'total_dibayarkan' => 150000,
            'total_tip' => 30000,
            'jenis_pengiriman' => 'Kurir Ojol'
        ]);

        // Pesanan selesai
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.3',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 2,
            'total_diskon_poin' => 0,
            'total_pesanan' => 850000, // Lapis legit
            'total_setelah_diskon' => 850000,
            'total_dibayarkan' => 900000,
            'total_tip' => 50000,
            'jenis_pengiriman' => 'Ambil Sendiri'
        ]);

        // Pesanan belum dibayar
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.4',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 1,
            'total_diskon_poin' => 0,
            'total_pesanan' => 75000, // Keripik kentang
            'total_setelah_diskon' => 75000,
            'total_dibayarkan' => null,
            'total_tip' => 0,
            'jenis_pengiriman' => 'Kurir Toko'
        ]);

        // Pesanan ditolak
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.5',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 1,
            'total_diskon_poin' => 0,
            'total_pesanan' => 120000, // Chocolate Bar 100gr
            'total_setelah_diskon' => 120000,
            'total_dibayarkan' => 120000,
            'total_tip' => 0,
            'jenis_pengiriman' => 'Kurir Toko'
        ]);

        // Dikirim kurir
        Pesanan::factory()->create([
            'id_pesanan' => '24.03.6',
            'id_pelanggan' => 2,
            'id_metode_pembayaran' => 1,
            'total_diskon_poin' => 6000,
            'total_pesanan' => 270000, // Chocolate Bar 100gr + Keripik kentang (2)
            'total_setelah_diskon' => 264000,
            'total_dibayarkan' => 270000,
            'total_tip' => 6000,
            'jenis_pengiriman' => 'Kurir Ojol'
        ]);
    }
}
