<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\ResepProduk;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResepProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resep = Produk::whereHas('resep')->with('resep.id_bahan_baku')->get();

        if (is_null($resep)) {
            return response([
                'message' => 'Resep tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Berhasil menampilkan semua resep produk',
            'data' => $resep
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_produk' => 'required',
            'bahan_baku' => 'required|array',
            'bahan_baku.*.id_bahan_baku' => 'required',
            'bahan_baku.*.jumlah' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $isProdukFound = Produk::find($storeData['id_produk']);

        if (is_null($isProdukFound)) return response([
            'message' => 'Produk tidak ditemukan',
            'data' => null
        ], 404);

        // References: https://laravel.com/docs/11.x/queries#where-exists-clauses
        foreach ($storeData['bahan_baku'] as $bahanBaku) {
            if (BahanBaku::where('id_bahan_baku', $bahanBaku['id_bahan_baku'])->doesntExist()) {
                return response([
                    'message' => 'Bahan Baku dengan id ' . $bahanBaku['id_bahan_baku'] . ' tidak ditemukan',
                    'data' => null
                ], 404);
            }

            ResepProduk::create([
                'id_produk' => $storeData['id_produk'],
                'id_bahan_baku' => $bahanBaku['id_bahan_baku'],
                'jumlah' => $bahanBaku['jumlah']
            ]);
        }

        return response([
            'message' => 'Berhasil menambahkan resep produk',
            'data' => $storeData,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id_produk)
    {
        $resep = Produk::whereHas('resep')->with('resep.id_bahan_baku')->find($id_produk);

        if (is_null($resep)) {
            return response([
                'message' => 'Resep tidak ditemukan',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Berhasil menampilkan resep produk ' . $resep->nama . ' ' . $resep->ukuran . '.',
            'data' => $resep
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id_produk)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'bahan_baku' => 'required|array',
            'bahan_baku.*.id_bahan_baku' => 'required',
            'bahan_baku.*.jumlah' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $isProdukFound = Produk::find($id_produk);

        if (is_null($isProdukFound)) {
            return response([
                'message' => 'Produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Make an empty array for store id resep product that will excluded to delete.
        $receivedIds = [];

        foreach ($storeData['bahan_baku'] as $bahanBaku) {
            // Check if id_resep_produk already exists, store the id. I will be update the data.
            if (isset($bahanBaku['id_resep_produk'])) {
                $receivedIds[] = $bahanBaku['id_resep_produk'];

                // Update existing record
                $resep = ResepProduk::find($bahanBaku['id_resep_produk']);

                if ($resep) {
                    $resep->id_bahan_baku = $bahanBaku['id_bahan_baku'] ?? $resep->id_bahan_baku;
                    $resep->jumlah = $bahanBaku['jumlah'] ?? $resep->jumlah;
                    $resep->save();
                }
            } else {
                // If id_resep_produk doesnt set, it will create a new one.
                $createdResepProduk = ResepProduk::create([
                    'id_produk' => $id_produk,
                    'id_bahan_baku' => $bahanBaku['id_bahan_baku'],
                    'jumlah' => $bahanBaku['jumlah'],
                ]);

                // Store the id in array to exclude from the delete operation
                $receivedIds[] = $createdResepProduk['id_resep_produk'];
            }
        }

        // Delete records that were not sent in the request
        ResepProduk::where('id_produk', $id_produk)
            ->whereNotIn('id_resep_produk', $receivedIds)
            ->delete();

        return response([
            'message' => 'Berhasil mengubah resep produk',
            'data' => $storeData,
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id_resep)
    {
        $resep = ResepProduk::find($id_resep);

        if (is_null($resep)) return response([
            'message' => 'Resep produk tidak ditemukan',
            'data' => null
        ], 404);

        if ($resep->delete()) {
            return response([
                'message' => 'Resep produk berhasil dihapus',
                'data' => $resep
            ], 200);
        }

        return response([
            'message' => 'Resep produk gagal dihapus',
            'data' => null
        ], 400);
    }
}
