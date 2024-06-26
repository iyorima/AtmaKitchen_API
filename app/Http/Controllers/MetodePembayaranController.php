<?php

namespace App\Http\Controllers;

use App\Models\MetodePembayaran;
use App\Http\Requests\StoreMetodePembayaranRequest;
use App\Http\Requests\UpdateMetodePembayaranRequest;

class MetodePembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $metodePembayaran = MetodePembayaran::all();

        if (count($metodePembayaran) > 0) {
            return response([
                'message' => 'Berhasil Mendapatkan semua metode pembayaran.',
                'data' => $metodePembayaran
            ], 200);
        }

        return response([
            'message' => 'metode pembayaran masih kosong',
            'data' => null
        ], 400);
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
    public function store(StoreMetodePembayaranRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MetodePembayaran $metodePembayaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MetodePembayaran $metodePembayaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMetodePembayaranRequest $request, MetodePembayaran $metodePembayaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MetodePembayaran $metodePembayaran)
    {
        //
    }
}
