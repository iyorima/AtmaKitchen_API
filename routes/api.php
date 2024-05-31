<?php

use App\Http\Controllers\PenarikanSaldoController;
use App\Http\Controllers\SaldoPelangganController;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\AlamatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukHampersController;
use App\Http\Controllers\PemesananBahanBakuController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DetailKeranjangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LaporanPengeluaranPemasukkanController;
use App\Http\Controllers\LaporanPenitipController;
use App\Http\Controllers\LaporanPresensiGajiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\PresensiAbsenController;
use App\Http\Controllers\ResepProdukController;
use App\Http\Controllers\PengeluaranLainnyaController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\StatusPesananController;
use App\Http\Controllers\MetodePembayaranController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('laporan-pengeluaran-pemasukkan', [LaporanPengeluaranPemasukkanController::class, 'laporanPengeluaranPemasukkan']);
Route::get('laporan-pengeluaran-pemasukkan/{tahun}/{bulan}', [LaporanPengeluaranPemasukkanController::class, 'getPengeluaranPemasukkan']);
Route::get('laporan-penitip', [LaporanPenitipController::class, 'rekapTransaksiPenitip']);
Route::get('laporan-penitip/{tahun}/{bulan}', [LaporanPenitipController::class, 'rekapTransaksiPenitipByDate']);
Route::get('laporan-presensi', [LaporanPresensiGajiController::class, 'generateLaporan']);
Route::get('laporan-presensi/{tahun}/{bulan}', [LaporanPresensiGajiController::class, 'generateLaporan']);

Route::resource('role', roleController::class);

Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/produk/kategori/{id}', [ProdukController::class, 'showByKategori']);
Route::get('/produk/penitip/{id}', [ProdukController::class, 'showByPenitip']);
Route::get('/produk/tanggal/{date}', [ProdukController::class, 'showByDate']);
Route::get('/produk/tanggal/{date}/{id}', [ProdukController::class, 'showReadyStockByDateAndId']);
Route::get('/produk/stok/{id}/{date}', [ProdukController::class, 'showStockByDateAndId']);
Route::post('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

Route::get('/hampers', [ProdukHampersController::class, 'index']);
Route::post('/hampers', [ProdukHampersController::class, 'store']);
Route::get('/hampers/{id}', [ProdukHampersController::class, 'show']);
Route::get('/hampers/stok/{id}/{date}', [ProdukHampersController::class, 'showStockByDateAndId']);
Route::post('/hampers/{id}', [ProdukHampersController::class, 'update']);
Route::delete('/hampers/{id}', [ProdukHampersController::class, 'destroy']);

Route::resource('bahan-baku/pemesanan', PemesananBahanBakuController::class);
Route::resource('penitip', PenitipController::class);
Route::resource('bahan-baku', BahanBakuController::class);
Route::resource('resep', ResepProdukController::class);
Route::resource('keranjang', KeranjangController::class);
Route::resource('detail-keranjang', DetailKeranjangController::class);
Route::resource('pengiriman', PengirimanController::class);
Route::resource('alamat', AlamatController::class);

Route::get('presensi/karyawan', [PresensiAbsenController::class, 'showByDate']);
Route::resource('presensi', PresensiAbsenController::class);

Route::resource('pelanggan', PelangganController::class);
Route::resource('pengeluaran-lainnya', PengeluaranLainnyaController::class);

Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);
Route::post('/pelanggan/{id_pelanggan}/pesanan/{id_pesanan}/upload-bukti-pembayaran', [PelangganController::class, 'uploadBuktiPembayaran']);

Route::get('/keranjang/{id}/{date}', [KeranjangController::class, 'show']);
Route::delete('/detail-keranjang/delete/{id}', [DetailKeranjangController::class, 'destroyAll']);

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AkunController::class, 'login']);
    Route::post('register', [AkunController::class, 'register']);
    Route::post('logout', [AkunController::class, 'logout']);
    Route::post('refresh', [AkunController::class, 'refresh']);
    Route::get('me', [AkunController::class, 'me']);
});


Route::get('/pesanan/delivery', [PesananController::class, 'getAllPesananNeedConfirmDelivery']);
Route::get('/pesanan/confirmpayments', [PesananController::class, 'getAllPesananNeedConfirmPayment']);
Route::get('/pesanan/in-process', [PesananController::class, 'getAllPesananInProcess']);
Route::get('/pesanan/rejected', [PesananController::class, 'getAllPesananRejected']);
Route::get('/pesanan/paymentverified', [PesananController::class, 'getAllPesananPaymentVerified']);

Route::post('/pesanan/status/{id_pesanan}', [PesananController::class, 'updateStatusPesanan']);
Route::post('/bahan-baku/laporan', [PesananController::class, 'getBahanBakuUsageByPeriod']);
Route::post('/bahan-baku/laporan/h', [PesananController::class, 'getBahanBakuUsageOfHampersByPeriod']);
Route::post('/pesanan/auto-update', [PesananController::class, 'autoUpdateStatueAfter2Days']);
Route::get('/pesanan/late', [PesananController::class, 'getAllPesananLatePayment']);

Route::put('/pesanan/confirmpayments/{id_pesanan}', [PesananController::class, 'createConfirmPayment']);
Route::put('/pesanan/confirm/{id_pesanan}', [PesananController::class, 'pesananAcceptedByCustomer']);
Route::get('/pesanan/laporan', [PesananController::class, 'getAllPendapatanBulanan']);


Route::post('/status', [StatusPesananController::class, 'store']);

Route::get('/pesanan/perlu-dikonfirmasi', [PesananController::class, 'indexPesananPerluDikonfirmasi']);
Route::post('/pesanan/{id}/terima', [PesananController::class, 'terimaPesanan']);
Route::post('/pesanan/{id}/tolak', [PesananController::class, 'tolakPesanan']);
Route::post('/pesanan/{id_pesanan}/process', [PesananController::class, 'processPesananByIdPesanan']);
Route::get('/pesanan/{id}/bahan-baku', [PesananController::class, 'listBahanBakuPerluDibeli']);

Route::get('/saldo', [SaldoPelangganController::class, 'index']);
Route::post('/saldo', [SaldoPelangganController::class, 'store']);
Route::get('/saldo/{id}', [SaldoPelangganController::class, 'show']);
Route::put('/saldo/{id}', [SaldoPelangganController::class, 'update']);
Route::delete('/saldo/{id}', [SaldoPelangganController::class, 'destroy']);
Route::get('/saldo/{id_akun}', [SaldoPelangganController::class, 'show']);
Route::put('/saldo/{id_akun}', [SaldoPelangganController::class, 'update']);

Route::get('/penarikan-saldo', [PenarikanSaldoController::class, 'index']);
Route::post('/penarikan-saldo', [PenarikanSaldoController::class, 'store']);
Route::get('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'show']);
Route::get('/penarikan-saldo/user/{id_akun}', [PenarikanSaldoController::class, 'showByCustomer']);
Route::put('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'update']);
Route::delete('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'destroy']);
Route::get('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'show']);

Route::get('/poin/{id}', [PoinController::class, 'showByPelanggan']);
Route::get('/poin/pesanan/{id_pelanggan}/{id_pesanan}', [PoinController::class, 'showByPesanan']);
Route::get('/poin/harga/{total_harga}', [PoinController::class, 'showGetPoin']);
Route::resource('poin', PoinController::class);

Route::resource('metode-pembayaran', MetodePembayaranController::class);
Route::resource('pesanan', PesananController::class);
Route::get('/pesanan/laporan/{date}', [PesananController::class, 'showByMonth']);


// jeha close
Route::resource('karyawan', KaryawanController::class);
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [authController::class, 'logout']);
    Route::put("/karyawan/profile", [KaryawanController::class, 'updateKaryawanProfile']);
});
