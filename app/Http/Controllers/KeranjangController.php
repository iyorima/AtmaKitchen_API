<?php

namespace App\Http\Controllers;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        // 📃 Validator
        $validate = Validator::make(
            $storeData,
            [
                'id_pelanggan' => 'required',
                'id_produk' => 'required',
                'jumlah' => 'required|min:1'
            ]
        );

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $keranjang = Keranjang::where('id_pelanggan', $request->id_pelanggan)->first();

        if (!is_null($keranjang)) {
            $detail_keranjang = DetailKeranjang::create([
                'id_keranjang' => $keranjang->id_keranjang,
                'id_produk' => $request->id_produk,
                'jumlah' => $request->jumlah
            ]);

            return response([
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'data' => $detail_keranjang,
            ], 200);
        }

        $keranjang = Keranjang::create([
            'id_pelanggan' => $request->id_pelanggan,
        ]);

        $detail_keranjang = DetailKeranjang::create([
            'id_keranjang' => $keranjang->id_keranjang,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->jumlah
        ]);

        return response([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => $detail_keranjang,
        ], 200);

        return response([
            'message' => 'Keranjang berhasil ditambahkan',
            'data' => $keranjang,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $keranjang = Keranjang::with('detail_keranjang')->where('id_pelanggan', $id)->get();

        if (!is_null($keranjang)) {
            return response([
                'message' => 'Keranjang ditemukan',
                'data' => $keranjang,
            ], 200);
        }

        return response([
            'message' => 'Keranjang tidak tersedia',
            'data' => null,
        ], 400);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $keranjang = Keranjang::with('detail_keranjang')->find($id);

        if (is_null($keranjang)) {
            return response([
                'message' => 'Keranjang tidak temukan',
                'data' => null,
            ], 400);
        }

        $keranjang->detail_keranjang->each->delete();


        $keranjang->delete();

        return response([
            'message' => 'Keranjang berhasil dihapus',
            'data' => $keranjang,
        ], 200);
    }
}
