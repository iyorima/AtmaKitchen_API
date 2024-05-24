<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Http\Requests\StorePengirimanRequest;
use App\Http\Requests\UpdatePengirimanRequest;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::has('pengiriman')->with('pengiriman', 'status_pesanan_latest')->orderBy('id_pesanan', 'desc')->get();

        if (is_null($pesanan)) {
            return response()->json([
                "message" => "Pesanan tidak ditemukan",
                "data" => null
            ], 404);
        }

        return response()->json([
            "message" => "Berhasil menampilkan semua pesanan",
            "data" => $pesanan
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengirimanRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $pengiriman = Pengiriman::find($id);

        if (is_null($pengiriman)) {
            return response()->json([
                "message" => "pengiriman tidak ditemukan",
                "data" => null
            ], 404);
        }

        return response()->json([
            "message" => "Berhasil menampilkan semua pengiriman",
            "data" => $pengiriman
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id_pengiriman)
    {
        $pengiriman = Pengiriman::find($id_pengiriman);

        if (is_null($pengiriman)) {
            return response()->json([
                "message" => "Pengiriman tidak ditemukan",
                "data" => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'jarak' => 'required',
            // 'harga' => 'required',
            // 'id_kurir' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        if ($updateData['jarak'] <= 5) {
            $updateData['harga'] = 10000;
        } else if ($updateData['jarak'] <= 10) {
            $updateData['harga'] = 15000;
        } else if ($updateData['jarak'] <= 15) {
            $updateData['harga'] = 20000;
        } else {
            $updateData['harga'] = 25000;
        }

        if ($pengiriman->update($updateData)) {
            return response()->json([
                "message" => "Berhasil menambahkan jarak kirim",
                "data" => $pengiriman
            ], 200);
        }

        return response()->json([
            "message" => "Gagal menambahkan jarak kirim",
            "data" => $pengiriman
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengiriman $pengiriman)
    {
        //
    }
}
