<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranLainnya;
use App\Http\Requests\StorepengeluaranLainnyaRequest;
use App\Http\Requests\UpdatepengeluaranLainnyaRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PengeluaranLainnyaController extends Controller
{
    /**
     * Menampilkan seluruh pengeluaran lainnya
     */
    public function index()
    {
        $pengeluaranLainnya = PengeluaranLainnya::with('karyawan')->get();

        if ($pengeluaranLainnya->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data pengeluaran lainnya',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'pengeluaran lainnya tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * membuat pengeluaran lainnya baru
     */
    public function store(Request $request)
    {
        if (auth()->check()) {
            $id_karyawan = auth()->user()->id_karyawan;
        } else {
              $id_karyawan = 1; 
        }
    
        $storeData = $request->all();
        unset($storeData['id_karyawan']);

        $validate = Validator::make($storeData, [
            'nama' => 'required',
            'biaya' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required|in:pengeluaran,pemasukan',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        $storeData['id_karyawan'] = $id_karyawan;

        $pengeluaranLainnya = PengeluaranLainnya::create($storeData);
    
        return response([
            'message' => 'berhasil membuat pengeluaran lainnya',
            'data' => $pengeluaranLainnya
        ], 200);
    }

    /**
     * Menampilkan data pengeluaran lainnya berdasarkan id
     */
    public function show($id)
    {
        $pengeluaranLainnya = PengeluaranLainnya::find($id);

        if (!is_null($pengeluaranLainnya)) {
            return response([
                'message' => 'pengeluaran lainnya ' . $pengeluaranLainnya->nama . ' ditemukan',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'data pengeluaran lainnya tidak ditemukan',
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
                'message' => 'data pengeluaran lainnya tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'biaya' => 'required',
            'tanggal' => 'required',
            'kategori' => 'required|in:pengeluaran,pemasukan',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $pengeluaranLainnya->update($updateData);

        if ($pengeluaranLainnya->save()) {
            return response([
                'message' => 'ubah data pengeluaran lainnya berhasil',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'ubah data pengeluaran lainnya ',
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
                'message' => 'data pengeluaran lainnya tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($pengeluaranLainnya->delete()) {
            return response([
                'message' => 'hapus pengeluaran lainnya berhasil',
                'data' => $pengeluaranLainnya
            ], 200);
        }

        return response([
            'message' => 'hapus pengeluaran lainnya gagal',
            'data' => null
        ], 400);
    }
}
