<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanBakus = BahanBaku::all();

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh data bahan baku',
            'data' => $bahanBakus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'satuan' => 'required|in:gr,butir,ml,buah', // DOCS: in is used to check only gr,butir,ml,buah are valid values to validate
            'stok' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $bahanBaku = BahanBaku::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan bahan baku baru',
            'data' => $bahanBaku
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bahanBaku = BahanBaku::find($id);
        try {
            if (!$bahanBaku) {
                return response()->json([
                    'message' => 'Bahan baku tidak ditemukan'
                ], 404);
            }
            return response([
                'message' => 'bahan baku ' . $bahanBaku->nama . ' ditemukan',
                'data' => $bahanBaku
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch bahan baku: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahanBaku = BahanBaku::find($id);

        if (!$bahanBaku) {
            return response([
                'message' => 'data bahan baku tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'satuan' => 'required|in:gr,butir,ml,buah', // DOCS: in is used to check only gr,butir,ml,buah are valid values to validate
            'stok' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $bahanBaku->update($updateData);

        return response()->json([
            'message' => 'ubah data bahan baku berhasil',
            'data' => $bahanBaku
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = BahanBaku::find($id);
        if (is_null($bahanBaku)) {
            return response([
                'message' => 'data  bahan baku tidak ditemukan',
                'data' => null
            ], 404);
        }
        if ($bahanBaku->delete()) {
            return response()->json([
                'message' => 'hapus bahan baku berhasil',
                'data' => $bahanBaku
            ]);
        } else {
            return response()->json([
                'message' => 'hapus bahan baku gagal'
            ], 500);
        }
    }
}
