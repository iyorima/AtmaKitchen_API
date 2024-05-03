<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranLainnya;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PengeluaranLainnyaController extends Controller
{
    /**
     * Menampilkan seluruh pengeluaran lainnya
     */
    public function index()
    {
        $pengeluaranLainnya = PengeluaranLainnya::with('karyawan:id_karyawan,nama')->get();

        if ($pengeluaranLainnya->isNotEmpty()) {
            return response([
                'message' => 'Arus kas berhasil ditemukan',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'Arus kas tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * membuat pengeluaran lainnya baru
     */
    public function store(Request $request)
    {
        // if (auth()->check()) {
        //     $id_karyawan = auth()->user()->id_karyawan;
        // } else {
        //     $id_karyawan = 1;
        // }

        $storeData = $request->all();
        // unset($storeData['id_karyawan']);

        $validate = Validator::make($storeData, [
            'id_karyawan' => 'required',
            'nama' => 'required',
            'biaya' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required|in:Pengeluaran,Pemasukkan',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // $storeData['id_karyawan'] = $id_karyawan;

        $pengeluaranLainnya = PengeluaranLainnya::create($storeData);

        return response([
            'message' => 'Arus kas berhasil ditambahkan',
            'data' => $pengeluaranLainnya
        ], 200);
    }

    /**
     * Menampilkan data pengeluaran lainnya berdasarkan id
     */
    public function show($id)
    {
        $pengeluaranLainnya = PengeluaranLainnya::with('karyawan')->find($id);

        if (!is_null($pengeluaranLainnya)) {
            return response([
                'message' => 'Arus kas ' . $pengeluaranLainnya->nama . ' ditemukan',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'Arus kas tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Mengubah data pengeluaran lainnya
     */
    public function update(Request $request, $id)
    {
        $pengeluaranLainnya = PengeluaranLainnya::find($id);

        if (is_null($pengeluaranLainnya)) {
            return response([
                'message' => 'Arus kas tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'biaya' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required|in:Pengeluaran,Pemasukkan',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        if ($pengeluaranLainnya->update($updateData)) {
            return response([
                'message' => 'Arus kas berhasil diubah',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'Arus kas gagal diubah ',
            'data' => null
        ], 400);
    }

    /**
     * Menghapus data pengeluaran lainnya
     */
    public function destroy($id)
    {
        $pengeluaranLainnya = pengeluaranLainnya::find($id);

        if (is_null($pengeluaranLainnya)) {
            return response([
                'message' => 'Arus kas tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($pengeluaranLainnya->delete()) {
            return response([
                'message' => 'Arus kas berhasil dihapus',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'Arus kas gagal dihapus',
            'data' => null
        ], 400);
    }
}
