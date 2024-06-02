<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Http\Requests\StorePengirimanRequest;
use App\Http\Requests\UpdatePengirimanRequest;
use App\Models\Pesanan;
use App\Models\StatusPesanan;
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
        /**
         * Retrieve a specific pengiriman by its ID.
         *
         * @param int $id_pengiriman The ID of the pengiriman to retrieve.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the pengiriman data or an error message.
         */
        $pengiriman = Pengiriman::find($id_pengiriman);

        if (is_null($pengiriman)) {
            return response()->json([
                "message" => "Pengiriman tidak ditemukan",
                "data" => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'jarak' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        /**
         * Calculate the delivery price based on the distance.
         *
         * @param array $updateData The data containing the distance and price to be updated.
         * @return void
         */
        $jarak = $updateData['jarak'];
        if ($jarak <= 5) {
            $harga = 10000;
        } else if ($jarak <= 10) {
            $harga = 15000;
        } else if ($jarak <= 15) {
            $harga = 20000;
        } else {
            $harga = 25000;
        }
        $updateData['harga'] = $harga;

        /**
         * Check if a status for the order is already created with the status "Menunggu pembayaran".
         * If not, create a new status for the order with the status "Menunggu pembayaran".
         *
         * @param  \App\Models\Pengiriman  $pengiriman
         * @return void
         */
        $isStatusCreated = StatusPesanan::where('id_pesanan', $pengiriman->id_pesanan)
            ->where('status', 'Menunggu pembayaran')
            ->first();

        if (!$isStatusCreated) {
            StatusPesanan::create([
                'id_pesanan' => $pengiriman->id_pesanan,
                'status' => 'Menunggu pembayaran'
            ]);
        }

        /**
         * Update the delivery distance and return a JSON response.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  int  $id
         * @return \Illuminate\Http\JsonResponse
         */
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
