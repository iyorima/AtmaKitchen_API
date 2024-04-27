<?php

use App\Http\Controllers\PenarikanSaldoController;
use App\Http\Controllers\SaldoPelangganController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;
use App\Http\Controllers\authController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukHampersController;
use App\Http\Controllers\PemesananBahanBakuController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DetailKeranjangController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PoinController;
use App\Http\Controllers\PresensiAbsenController;
use App\Http\Controllers\ResepProdukController;
use App\Http\Controllers\PengeluaranLainnyaController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PesananController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/register', [authController::class, 'register']);
Route::get('/register/verify/{verify_key}', [authController::class, 'verify']);
Route::post('/login', [authController::class, 'login']);

Route::post('/send-otp',  [ForgotPasswordController::class, 'sendOTP']);
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOTP']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/role', [roleController::class, 'index']);
Route::post('/role', [roleController::class, 'store']);
Route::get('/role/{id}', [roleController::class, 'show']);
Route::put('/role/{id}', [roleController::class, 'update']);
Route::delete('/role/{id}', [roleController::class, 'destroy']);

Route::get('/produk', [ProdukController::class, 'index']);
Route::post('/produk', [ProdukController::class, 'store']);
Route::get('/produk/{id}', [ProdukController::class, 'show']);
Route::get('/produk/kategori/{id}', [ProdukController::class, 'showByKategori']);
Route::get('/produk/penitip/{id}', [ProdukController::class, 'showByPenitip']);
Route::post('/produk/{id}', [ProdukController::class, 'update']);
Route::delete('/produk/{id}', [ProdukController::class, 'destroy']);

Route::get('/hampers', [ProdukHampersController::class, 'index']);
Route::post('/hampers', [ProdukHampersController::class, 'store']);
Route::get('/hampers/{id}', [ProdukHampersController::class, 'show']);
Route::post('/hampers/{id}', [ProdukHampersController::class, 'update']);
Route::delete('/hampers/{id}', [ProdukHampersController::class, 'destroy']);

Route::get('/bahan-baku/pemesanan', [PemesananBahanBakuController::class, 'index']);
Route::post('/bahan-baku/pemesanan', [PemesananBahanBakuController::class, 'store']);
Route::get('/bahan-baku/pemesanan/{id}', [PemesananBahanBakuController::class, 'show']);
Route::post('/bahan-baku/pemesanan/{id}', [PemesananBahanBakuController::class, 'update']);
Route::delete('/bahan-baku/pemesanan/{id}', [PemesananBahanBakuController::class, 'destroy']);

Route::get('/penitip', [PenitipController::class, 'index']);
Route::post('/penitip', [PenitipController::class, 'store']);
Route::get('/penitip/{id}', [PenitipController::class, 'show']);
Route::put('/penitip/{id}', [PenitipController::class, 'update']);
Route::delete('/penitip/{id}', [PenitipController::class, 'destroy']);

Route::get('/bahan-baku', [BahanBakuController::class, 'index']);
Route::post('/bahan-baku', [BahanBakuController::class, 'store']);
Route::get('/bahan-baku/{id}', [BahanBakuController::class, 'show']);
Route::put('/bahan-baku/{id}', [BahanBakuController::class, 'update']);
Route::delete('/bahan-baku/{id}', [BahanBakuController::class, 'destroy']);

Route::get('/karyawan', [KaryawanController::class, 'index']);
Route::post('/karyawan', [KaryawanController::class, 'store']);
Route::get('/karyawan/{id}', [KaryawanController::class, 'show']);
Route::put('/karyawan/{id}', [KaryawanController::class, 'update']);
Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy']);

Route::get('/resep', [ResepProdukController::class, 'index']);
Route::post('/resep', [ResepProdukController::class, 'store']);
Route::get('/resep/{id}', [ResepProdukController::class, 'show']); // id: id produk @Produk
Route::put('/resep/{id}', [ResepProdukController::class, 'update']); // id: id produk @Produk
Route::delete('/resep/{id}', [ResepProdukController::class, 'destroy']); // id: id resep @ResepProduk ⛔ It doesnt needed anymore!

Route::resource('presensi', PresensiAbsenController::class);

Route::resource('pelanggan', PelangganController::class);

Route::get('/pengeluaran-lainnya', [PengeluaranLainnyaController::class, 'index']);
Route::post('/pengeluaran-lainnya', [PengeluaranLainnyaController::class, 'store']);
Route::get('/pengeluaran-lainnya/{id}', [PengeluaranLainnyaController::class, 'show']);
Route::put('/pengeluaran-lainnya/{id}', [PengeluaranLainnyaController::class, 'update']);
Route::delete('/pengeluaran-lainnya/{id}', [PengeluaranLainnyaController::class, 'destroy']);

// Route::resource('pesanan', PesananController::class);

Route::get('/pelanggan', [PelangganController::class, 'index']);
Route::post('/pelanggan', [PelangganController::class, 'store']);
Route::get('/pelanggan/{id}', [PelangganController::class, 'show']);
Route::put('/pelanggan/{id}', [PelangganController::class, 'update']);
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy']);
Route::post('/pelanggan/{id_pelanggan}/pesanan/{id_pesanan}/upload-bukti-pembayaran', [PelangganController::class, 'uploadBuktiPembayaran']);


Route::resource('keranjang', KeranjangController::class);
Route::resource('detail-keranjang', DetailKeranjangController::class);
Route::resource('pengiriman', PengirimanController::class);

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [authController::class, 'logout']);

    Route::resource('pesanan', PesananController::class);
    // Route::get('/user', [usersController::class, 'index']);
});

Route::get('/saldo', [SaldoPelangganController::class, 'index']);
Route::post('/saldo', [SaldoPelangganController::class, 'store']);
Route::get('/saldo/{id}', [SaldoPelangganController::class, 'show']);
Route::put('/saldo/{id}', [SaldoPelangganController::class, 'update']);
Route::delete('/saldo/{id}', [SaldoPelangganController::class, 'destroy']);

Route::get('/penarikan-saldo', [PenarikanSaldoController::class, 'index']);
Route::post('/penarikan-saldo', [PenarikanSaldoController::class, 'store']);
Route::get('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'show']);
Route::put('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'update']);
Route::delete('/penarikan-saldo/{id}', [PenarikanSaldoController::class, 'destroy']);


// Route::middleware(['auth:api'])->group(function () {
//     Route::post('/logout', [authController::class, 'logout']);
// });
