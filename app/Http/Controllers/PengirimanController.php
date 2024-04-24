<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Http\Requests\StorePengirimanRequest;
use App\Http\Requests\UpdatePengirimanRequest;
use App\Models\Pesanan;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::has('pengiriman')->with('pengiriman', 'status_pesanan_latest')->get();

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(Pengiriman $pengiriman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengirimanRequest $request, Pengiriman $pengiriman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengiriman $pengiriman)
    {
        //
    }
}
