<?php

namespace App\Http\Controllers;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DetailKeranjangController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $storeData = $request->all();

        // 📃 Validator
        $validate = Validator::make(
            $storeData,
            [
                'id_produk' => 'required',
                'jumlah' => 'required|int|min:1'
            ],
            [
                'jumlah' => 'Jumlah harus lebih dari atau sama dengan 1'
            ]
        );

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $detail_keranjang = DetailKeranjang::find($id);

        if (!is_null($detail_keranjang)) {
            $detail_keranjang->update($storeData);

            return response([
                'message' => 'Keranjang berhasil diperbarui',
                'data' => $detail_keranjang,
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $detail_keranjang = DetailKeranjang::find($id);

        if ($detail_keranjang->delete()) {
            return response([
                'message' => 'Produk berhasil dihapus dari keranjang',
                'data' => $detail_keranjang,
            ], 200);
        }

        return response([
            'message' => 'Produk gagal dihapus dari keranjang',
            'data' => $detail_keranjang,
        ], 400);
    }

    public function destroyAll($id)
    {
        $keranjang = Keranjang::where('id_pelanggan', $id)->first();

        if (is_null($keranjang)) {
            return response([
                'message' => 'Tidak ada keranjang untuk dihapus',
                'data' => null,
            ], 404);
        }

        $detail_keranjang = DetailKeranjang::where('id_keranjang', $keranjang->id_keranjang)->get();

        if ($detail_keranjang->isEmpty()) {
            return response([
                'message' => 'Tidak ada detail keranjang untuk dihapus',
                'data' => null,
            ], 404);
        }

        $detail_keranjang->each->delete();

        return response([
            'message' => 'Semua detail keranjang berhasil dihapus',
            'data' => null,
        ], 200);
    }
}
