<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // ============= Nathan =============
            RoleSeeder::class,
            PenitipSeeder::class,
            AkunSeeder::class,
            PelangganSeeder::class,
            AlamatSeeder::class,
            KategoriProdukSeeder::class,
            ProdukSeeder::class,
            ProdukHampersSeeder::class,
            // ============= Jeha =============
            BahanBakuSeeder::class,
            KaryawanSeeder::class,
            KategoriPengirimanSeeder::class,
            MetodePembayaranSeeder::class,
            PembayaranGajiSeeder::class,
            PemesananBahanBakuSeeder::class,
            PenarikanSaldoSeeder::class,
            PengeluaranLainnyaSeeder::class,
            // ============= Yori =============
            PresensiAbsenSeeder::class,
            ResepProdukSeeder::class,
            PesananSeeder::class,
            SaldoPelangganSeeder::class,
            PoinSeeder::class,
            PengirimanSeeder::class,
            DetailPesananSeeder::class,
            StatusPesananSeeder::class,
        ]);
    }
}
