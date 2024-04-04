<?php

namespace App\Http\Controllers;

use App\Models\ProdukHampers;
use App\Models\DetailHampers;
use App\Http\Requests\StoreProdukHampersRequest;
use App\Http\Requests\UpdateProdukHampersRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProdukHampersController extends Controller
{
    /**
     * Menampilkan seluruh data hampers
     */
    public function index()
    {
        $produkHampers = ProdukHampers::with(['detailHampers.produk'])->get();

        if ($produkHampers->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data produk hampers',
                'data' => $produkHampers
            ], 200);
        }

        return response([
            'message' => 'produk hampers tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * Membuat data produk hampers
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'harga_jual' => 'required',
            'produk' => 'required|array',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $hampersData = [
            'nama' => $storeData['nama'],
            'harga_jual' => $storeData['harga_jual'],
        ];

        $imagePath = $request->file('image')->store('product', 'public');
        $hampersData['image'] = $imagePath;

        $hampers = ProdukHampers::create($hampersData);

        foreach ($storeData['produk'] as $id_produk) {
            DetailHampers::create([
                'id_produk_hampers' => $hampers->id_produk_hampers,
                'id_produk' => $id_produk,
            ]);
        }

        $produkHampers = ProdukHampers::with(['detailHampers.produk'])->find($hampers->id_produk_hampers);

        return response([
            'message' => 'Add Product Success',
            'data' => $produkHampers
        ], 200);
    }

    /**
     * Menampilkan hampers berdasarkan id produk hampers
     */
    public function show($id)
    {
        $produkHampers = ProdukHampers::with(['detailHampers.produk'])->find($id);

        if (!is_null($produkHampers)) {
            return response([
                'message' => 'produk hampers ' . $produkHampers->nama . ' ditemukan',
                'data' => $produkHampers
            ], 200);
        }

        return response([
            'message' => 'produk hampers tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Mengubah data produk hampers tertentu
     */
    public function update(Request $request, $id)
    {
        $produkHampers = ProdukHampers::with(['detailHampers.produk'])->find($id);

        if (is_null($produkHampers)) {
            return response([
                'message' => 'produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'harga_jual' => 'required',
            'produk' => 'required|array',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $produkHampers->detailHampers()->delete();
        Storage::disk('public')->delete($produkHampers->image);

        $hampersData = [
            'nama' => $updateData['nama'],
            'harga_jual' => $updateData['harga_jual'],
        ];

        $imagePath = $request->file('image')->store('product', 'public');
        $hampersData['image'] = $imagePath;

        $produkHampers->update($hampersData);

        foreach ($updateData['produk'] as $id_produk) {
            DetailHampers::create([
                'id_produk_hampers' => $produkHampers->id_produk_hampers,
                'id_produk' => $id_produk,
            ]);
        }

        $updatedProdukHampers = ProdukHampers::with(['detailHampers.produk'])->find($id);

        if ($produkHampers->save()) {
            return response([
                'message' => 'produk berhasil diubah',
                'data' => [
                    'produk' => $updatedProdukHampers,
                ]
            ], 200);
        }

        return response([
            'message' => 'ubah produk hampers gagal',
            'data' => null
        ], 400);
    }

    /**
     * Menghapus data produk hampers
     */
    public function destroy($id)
    {
        $produkHampers = ProdukHampers::with(['detailHampers.produk'])->find($id);

        if (is_null($produkHampers)) {
            return response([
                'message' => 'produk hampers tidak ditemukan',
                'data' => null
            ], 404);
        }

        $produkHampers->detailHampers()->delete();
        Storage::disk('public')->delete($produkHampers->image);

        if ($produkHampers->delete()) {
            return response([
                'message' => 'produk ' . $produkHampers->nama . ' berhasil dihapus',
                'data' => $produkHampers
            ], 200);
        }

        return response([
            'message' => 'produk hampers gagal dihapus',
            'data' => null
        ], 400);
    }
}
