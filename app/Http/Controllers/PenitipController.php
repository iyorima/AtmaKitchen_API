<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use App\Http\Requests\StorePenitipRequest;
use App\Http\Requests\UpdatePenitipRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PenitipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $penitip = Penitip::withCount('produk as produk_titipan_count')->get();

        if ($penitip->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data penitip',
                'data' => $penitip
            ], 200);
        }

        return response([
            'message' => 'data penitip tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $jumlahPenitip = Penitip::withTrashed()->count();

        // Tambahkan 1 pada jumlah penitip
        $newId = 'penitip-' . str_pad($jumlahPenitip + 1, 2, '0', STR_PAD_LEFT);

        // Masukkan id_penitip baru ke dalam data yang akan disimpan
        $storeData['id_penitip'] = $newId;


        $penitip = Penitip::create($storeData);
        return response([
            'message' => 'Berhasil menambahkan penitip baru',
            'data' => $penitip
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $penitip = Penitip::with('produk')->find($id);

        if ($penitip) {
            return response([
                'message' => ' penitip ' . $penitip->nama . ' ditemukan',
                'data' => $penitip
            ], 200);
        }

        return response([
            'message' => 'data penitip tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response([
                'message' => 'data penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $penitip->update($updateData);

        return response([
            'message' => 'ubah data penitip berhasil',
            'data' => $penitip
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penitip = Penitip::find($id);

        if (!$penitip) {
            return response([
                'message' => 'data penitip tidak ditemukan',
                'data' => null
            ], 404);
        }

        $penitip->delete();

        return response([
            'message' => 'hapus penitip berhasil',
            'data' => $penitip
        ], 200);
    }
}
