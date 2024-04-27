<?php

namespace App\Http\Controllers;

use App\Models\SaldoPelanggan;
use App\Http\Requests\StoreSaldoPelangganRequest;
use App\Http\Requests\UpdateSaldoPelangganRequest;
use App\Models\Akun;
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
        if (is_null($saldoPelanggan)) return response()->json([
            "message" => "Saldo tidak ditemukan",
            "data" => null
        ], 404);

        return response()->json([
            "message" => "Berhasil menampilkan semua saldo",
            "data" => $saldoPelanggan
        ], 200);
       
    }

    /**
     * Menyimpan saldo pelanggan yang baru.
     */
    public function store(StoreSaldoPelangganRequest $request)
    {
        $saldoPelanggan = $request->all();

        $validate = Validator::make($saldoPelanggan, [
            'saldo' => 'required',
            'id_akun' => 'required|exists:akuns,id_akun',
        ]);
    

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }


        return response()->json([
            'message' => 'Berhasil menambahkan saldo',
            'data' => $saldoPelanggan
        ], 201);

      
    }

    /**
     * Menampilkan saldo pelanggan berdasarkan ID.
     */
    public function show(int $id_saldo_pelanggan)
    {
        $saldoPelanggan = SaldoPelanggan::with('id_akun')->find($id_saldo_pelanggan);

        if (!$saldoPelanggan) {
            return response()->json(['message' => 'Saldo tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan data saldo pelanggan ' .  $saldoPelanggan->id_akun->pelanggan->nama . '',
            'data' => [
                'pelanggan' => $saldoPelanggan
            ]
        ], 200);

    }

    /**
     * Memperbarui saldo pelanggan yang ada.
     */
    public function update(UpdateSaldoPelangganRequest $request, int $id_saldo_pelanggan)
    {
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'saldo' => 'required',
            'id_akun' => 'required|exists:akuns,id_akun',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $saldoPelanggan = SaldoPelanggan::find($id_saldo_pelanggan);

        if (is_null($saldoPelanggan)) {
            return response()->json([
                "message" => "Saldo tidak ditemukan",
                "data" => null
            ], 404);
        }
    
        $akun = Akun::find($storeData['id_akun']);
    
        if (is_null($akun)) {
            return response()->json([
                "message" => "Akun tidak ditemukan",
                "data" => null
            ], 404);
        }
    
        $saldoPelanggan->saldo += $storeData['saldo'];
    
        $saldoPelanggan->save();
    
        return response()->json([
            "message" => "Berhasil update saldo",
            "data" => $saldoPelanggan
        ], 200);

    }

    /**
     * Menghapus saldo pelanggan.
     */
    public function destroy(SaldoPelanggan $saldoPelanggan)
    {
       
    }
}
