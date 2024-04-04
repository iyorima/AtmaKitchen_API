<?php

namespace App\Http\Controllers;

use App\Models\PemesananBahanBaku;
use App\Http\Requests\StorePemesananBahanBakuRequest;
use App\Http\Requests\UpdatePemesananBahanBakuRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PemesananBahanBakuController extends Controller
{
    /**
     * Menampilkan seluruh pemesanan bahan baku
     */
    public function index()
    {
        $pemesananBahanBaku = PemesananBahanBaku::with('bahan_baku')->get();

        if ($pemesananBahanBaku->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data pemesanan bahan baku',
                'data' => $pemesananBahanBaku
            ], 200);
        }

        return response([
            'message' => 'pemesanan bahan baku tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * membuat pemesanan bahan baku baru
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_bahan_baku' => 'required',
            'nama' => 'required',
            'satuan' => 'required',
            'jumlah' => 'required',
            'harga_beli' => 'required',
            'total' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $pemesananBahanBaku = PemesananBahanBaku::create($storeData);

        return response([
            'message' => 'berhasil membuat pemesanan bahan baku baru',
            'data' => $pemesananBahanBaku
        ], 200);
    }

    /**
     * Menampilkan data pemesanan bahan baku berdasarkan id
     */
    public function show($id)
    {
        $pemesananBahanBaku = PemesananBahanBaku::with('bahan_baku')->find($id);

        if (!is_null($pemesananBahanBaku)) {
            return response([
                'message' => 'pemesanan bahan baku ' . $pemesananBahanBaku->nama . ' ditemukan',
                'data' => $pemesananBahanBaku
            ], 200);
        }

        return response([
            'message' => 'data pemesanan bahan baku tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Mengubah data pemesanan bahan baku
     */
    public function update(Request $request, $id)
    {
        $pemesananBahanBaku = PemesananBahanBaku::find($id);

        if (is_null($pemesananBahanBaku)) {
            return response([
                'message' => 'data pemesanan bahan baku tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_bahan_baku' => 'required',
            'nama' => 'required',
            'satuan' => 'required',
            'jumlah' => 'required',
            'harga_beli' => 'required',
            'total' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $pemesananBahanBaku->update($updateData);

        if ($pemesananBahanBaku->save()) {
            return response([
                'message' => 'ubah data pemesanan bahan baku berhasil',
                'data' => $pemesananBahanBaku
            ], 200);
        }

        return response([
            'message' => 'ubah data pemesanan bahan baku ',
            'data' => null
        ], 400);
    }

    /**
     * Menghapus data pemesanan bahan baku
     */
    public function destroy($id)
    {
        $pemesananBahanBaku = PemesananBahanBaku::find($id);

        if (is_null($pemesananBahanBaku)) {
            return response([
                'message' => 'data pemesanan bahan baku tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($pemesananBahanBaku->delete()) {
            return response([
                'message' => 'hapus pemesanan bahan baku berhasil',
                'data' => $pemesananBahanBaku
            ], 200);
        }

        return response([
            'message' => 'hapus pemesanan bahan baku gagal',
            'data' => null
        ], 400);
    }
}
