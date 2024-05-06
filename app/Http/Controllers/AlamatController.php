<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
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

        $validate = Validator::make($storeData, [
            'id_pelanggan' => 'required',
            'label' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $alamat = Alamat::create($storeData);

        return response([
            'message' => 'Berhasil menambahkan alamat baku baru',
            'data' => $alamat
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('alamat')->find($id_pelanggan);
        return response()->json([
            'message' => 'Berhasil mendapatkan data alamat pelanggan',
            'data' => $pelanggan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $alamat = Alamat::find($id);

        if (!$alamat) {
            return response([
                'message' => 'data bahan baku tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pelanggan' => 'required',
            'label' => 'required',
            'nama' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $alamat->update($updateData);


        return response()->json([
            'message' => 'Alamat berhasil diubah',
            'data' => $alamat
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $alamat = Alamat::find($id);
        if (is_null($alamat)) {
            return response([
                'message' => 'Alamat tidak ditemukan',
                'data' => null
            ], 404);
        }
        if ($alamat->delete()) {
            return response()->json([
                'message' => 'Alamat berhasil dihapus',
                'data' => $alamat
            ]);
        } else {
            return response()->json([
                'message' => 'Alamat gagal dihapus'
            ], 500);
        }
    }
}
