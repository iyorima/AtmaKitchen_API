<?php

namespace App\Http\Controllers;

use App\Models\SaldoPelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaldoPelangganController extends Controller
{
    /**
     * Menampilkan saldo pelanggan.
     */
    public function index()
    {
        $saldoPelanggan = SaldoPelanggan::all();
        if ($saldoPelanggan->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data saldo pelanggan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh data saldo pelanggan',
            'data' => $saldoPelanggan
        ]);
    }

    public function show(int $id_akun) //ini aku tampilin berdasarkan id_akun jadi get by customer gtu
{
    $saldoPelanggan = SaldoPelanggan::where('id_akun', $id_akun)->first();

    if (!$saldoPelanggan) {
        return response()->json(['message' => 'Saldo tidak ditemukan untuk akun ini'], 404);
    }
    return response()->json([
        'message' => 'Berhasil mendapatkan data saldo pelanggan',
        'data' => $saldoPelanggan
    ]);
}

}
