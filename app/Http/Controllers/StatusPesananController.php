<?php

namespace App\Http\Controllers;

use App\Models\StatusPesanan;
use App\Http\Requests\StoreStatusPesananRequest;
use App\Http\Requests\UpdateStatusPesananRequest;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusPesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pesanan' => 'required',
            'id_karyawan' => 'required',
            'status' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $pesanan = Pesanan::find($updateData['id_pesanan']);

        if (is_null($pesanan)) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        $status = StatusPesanan::create($updateData);

        return response()->json([
            'message' => 'Status pesanan berhasil ditambahkan',
            'data' => $status
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusPesanan $statusPesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusPesanan $statusPesanan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id_status_pesanan)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusPesanan $statusPesanan)
    {
        //
    }
}
