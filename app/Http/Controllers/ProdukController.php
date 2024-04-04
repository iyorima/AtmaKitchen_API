<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Menampilkan seluruh data produk
     */
    public function index()
    {
        $produk = Produk::with('images')->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data produk',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'Produk tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * Membuat produk baru
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_kategori' => 'required',
            'id_penitip',
            'nama' => 'required',
            'kapasitas' => 'required',
            'ukuran' => 'required',
            'harga_jual' => 'required',
            'image1' => 'image|mimes:jpeg,png,jpg',
            'image2' => 'image|mimes:jpeg,png,jpg',
            'image3' => 'image|mimes:jpeg,png,jpg',
            'image4' => 'image|mimes:jpeg,png,jpg',
            'image5' => 'image|mimes:jpeg,png,jpg',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $produkData = [
            'id_kategori' => $storeData['id_kategori'],
            'nama' => $storeData['nama'],
            'kapasitas' => $storeData['kapasitas'],
            'ukuran' => $storeData['ukuran'],
            'harga_jual' => $storeData['harga_jual'],
        ];

        if (isset($storeData['id_penitip']))
            $produkData['id_penitip'] = $storeData['id_penitip'];
        else
            $produkData['id_penitip'] = null;


        $produk = Produk::create($produkData);

        $imagePaths = [];

        for ($i = 1; $i <= 5; $i++) {
            $imageKey = 'image' . $i;
            if ($request->hasFile($imageKey)) {
                $imagePath = $request->file($imageKey)->store('public/product');
                ProdukImage::create([
                    'id_produk' => $produk->id_produk,
                    'image' => $imagePath
                ]);
                $imagePaths[] = $imagePath;
            }
        }

        $produk['images'] = $imagePaths;

        return response([
            'message' => 'Add Product Success',
            'data' => $produk
        ], 200);
    }

    /**
     * Menampilkan produk berdasarkan id produk
     */
    public function show($id)
    {
        $produk = Produk::with('images')->find($id);

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'produk ' . $produk->nama . ' ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Menampilkan produk berdasarkan id kategori
     */
    public function showByKategori($id)
    {
        $produk = Produk::with('images')->where('id_kategori', $id)->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'produk ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Menampilkan produk berdasarkan id penitip
     */
    public function showByPenitip($id)
    {
        $produk = Produk::with('images')->where('id_penitip', $id)->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'produk ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Mengubah data produk
     * TODO: cari cara supaya foto bisa dinamis
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::with('images')->find($id);

        if (is_null($produk)) {
            return response([
                'message' => 'produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_kategori' => 'required',
            'id_penitip',
            'nama' => 'required',
            'kapasitas' => 'required',
            'ukuran' => 'required',
            'harga_jual' => 'required',
            'image1' => 'image|mimes:jpeg,png,jpg',
            'image2' => 'image|mimes:jpeg,png,jpg',
            'image3' => 'image|mimes:jpeg,png,jpg',
            'image4' => 'image|mimes:jpeg,png,jpg',
            'image5' => 'image|mimes:jpeg,png,jpg',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        foreach ($produk->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }

        $produkData = [
            'id_kategori' => $updateData['id_kategori'],
            'nama' => $updateData['nama'],
            'kapasitas' => $updateData['kapasitas'],
            'ukuran' => $updateData['ukuran'],
            'harga_jual' => $updateData['harga_jual'],
        ];

        $produk->update($produkData);

        $imagePaths = [];
        for ($i = 1; $i <= 5; $i++) {
            $imageKey = 'image' . $i;
            if ($request->hasFile($imageKey)) {
                $imagePath = $request->file($imageKey)->store('public/product');
                ProdukImage::create([
                    'id_produk' => $produk->id_produk,
                    'image' => $imagePath
                ]);
                $imagePaths[] = $imagePath;
            }
        }

        $updatedProduk = Produk::with('images')->find($id);

        if ($produk->save()) {
            return response([
                'message' => 'produk berhasil diubah',
                'data' => [
                    'produk' => $updatedProduk,
                    'image' => $imagePaths
                ]
            ], 200);
        }

        return response([
            'message' => 'ubah produk gagal',
            'data' => null
        ], 400);
    }

    /**
     * Menghapus data produk berdasarkan id produk
     */
    public function destroy($id)
    {
        $produk = Produk::with('images')->find($id);

        if (is_null($produk)) {
            return response([
                'message' => 'produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        foreach ($produk->images as $image) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
        }

        if ($produk->delete()) {
            return response([
                'message' => 'produk ' . $produk->nama . ' berhasil dihapus',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk gagal dihapus',
            'data' => null
        ], 400);
    }

    /**
     * Mencari data produk berdasarkan nama
     */
    public function search(Request $request)
    {
        $keyword = $request->all();

        $validate = Validator::make($keyword, [
            'keyword' => 'string|max:255',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $produk = Produk::where('name', 'ilike', '%' . $keyword['keyword'] . '%')->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'pencarian berhasil',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }
}
