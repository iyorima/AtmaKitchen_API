<?php

namespace App\Http\Controllers;

use App\Models\PemesananBahanBaku;
use App\Models\BahanBaku;
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

        if ($request->has('id_bahan_baku') && $request->id_bahan_baku == null) {
            $storeDataBahanBaku['nama'] = $request->nama;
            $storeDataBahanBaku['satuan'] = $request->satuan;
            $storeDataBahanBaku['stok'] = $request->jumlah;
            $storeDataBahanBaku['stok_minumum'] = 1;
            $bahanBaku = BahanBaku::create($storeDataBahanBaku);

            $storeData['id_bahan_baku'] = $bahanBaku['id_bahan_baku'];
        } else {
            $bahanBaku = BahanBaku::find($request->id_bahan_baku);
            if ($bahanBaku) {
                $storeData['nama'] = $bahanBaku->nama;
                $bahanBaku->stok += $request->jumlah;
                $bahanBaku->save();
            }
        }

        $validatePemesanan = Validator::make($storeData, [
            'id_bahan_baku' => 'required',
            'satuan' => 'required|in:gr,butir,ml,buah',
            'jumlah' => 'required',
            'harga_beli' => 'required',
        ]);

        if ($validatePemesanan->fails()) {
            return response(['message' => $validatePemesanan->errors()], 400);
        }

        $storeData['total'] = $storeData['jumlah'] * $storeData['harga_beli'];
        $pemesananBahanBaku = PemesananBahanBaku::create($storeData);

        return response([
            'message' => 'Berhasil membuat pemesanan bahan baku baru',
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
            'satuan' => 'required|in:gr,butir,ml,buah',
            'jumlah' => 'required',
            'harga_beli' => 'required',
            'total' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        if ($request->has('id_bahan_baku') && $request->id_bahan_baku != null) {
            $bahanBaku = BahanBaku::find($request->id_bahan_baku);
            if ($bahanBaku) {
                $updateData['nama'] = $bahanBaku->nama;

                $stokDifference = $updateData['jumlah'] - $pemesananBahanBaku->jumlah;
                $bahanBaku->stok += $stokDifference;
                $bahanBaku->save();
            }
        }

        if ($pemesananBahanBaku->update($updateData)) {
            return response([
                'message' => 'ubah data pemesanan bahan baku berhasil',
                'data' => $pemesananBahanBaku
            ], 200);
        }

        return response([
            'message' => 'ubah data pemesanan bahan baku gagal',
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

        $bahanBaku = BahanBaku::find($pemesananBahanBaku->id_bahan_baku);
        if ($bahanBaku) {
            $bahanBaku->stok -= $pemesananBahanBaku->jumlah;
            $bahanBaku->save();
        }

        $pemesananBahanBaku->delete();

        return response([
            'message' => 'Hapus pemesanan bahan baku berhasil',
            'data' => $pemesananBahanBaku
        ], 200);
    }
}
