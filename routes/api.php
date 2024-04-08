<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usersController;
use App\Http\Controllers\authController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProdukHampersController;
use App\Http\Controllers\PemesananBahanBakuController;
use App\Models\PemesananBahanBaku;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\KaryawanController;

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

Route::get('/role', [roleController::class, 'index']);
Route::post('/role', [roleController::class, 'store']);
Route::get('/role/{id}', [roleController::class, 'show']);
Route::post('/role/{id}', [roleController::class, 'update']);
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

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', [usersController::class, 'index']);
});
