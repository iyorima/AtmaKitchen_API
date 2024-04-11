<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Http\Requests\StorePelangganRequest;
use App\Http\Requests\UpdatePelangganRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Akun;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = Pelanggan::all();

        if ($pelanggan->isNotEmpty()) {
        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh data pelanggan',
            'data' => $pelanggan
        ]);
        }
        return response([
            'message' => 'data pelanggan tidak ditemukan',
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
            'tgl_lahir' => 'required',
            'telepon' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }

        $akun = Akun::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_role' => $request->id_role
        ]);

        $pelanggan = Pelanggan::create([
            'id_akun' => $akun->id_akun,
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'telepon' => $request->telepon,
        ]);

        return response()->json([
            'message' => 'Berhasil menambahkan pelanggan baru',
            'data' => $pelanggan
        ], 201);
    }

    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $pelanggan = Pelanggan::find($id);

    if (!$pelanggan) {
        return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
    }

    try {
        $pesananBelumSelesai = $pelanggan->pesananBelumSelesai()->whereNull('total_dibayarkan')->get();
        $historiPesanan = $pelanggan->historiPesanan()->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan data pelanggan ' . $pelanggan->nama .'',
            'data' => [
                'pelanggan' => $pelanggan,
                'pesanan' => $pesananBelumSelesai->load('detailPesanan.produk'), // Eager load the detailPesanan and produk relationships for pending orders
                'histori_pesanan' => $historiPesanan,
            ]
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Gagal mendapatkan data pelanggan: ' . $e->getMessage()
        ], 500);
    }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        
        $validate = Validator::make($updateData, [
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'telepon' => 'required',
            'email' => 'required|email:rfc,dns|unique:akuns,email,' . $request->id_akun . ',id_akun',
            'password' => 'nullable', // Tidak wajib
            'id_role' => 'required',
        ]);
    
        if ($validate->fails()) {
            return response()->json(['message' => $validate->errors()], 400);
        }
    
        $pelanggan = Pelanggan::with('akun')->find($id);
       
        if (!$pelanggan) {
            return response(['message' => 'Pelanggan tidak ditemukan'], 404);
        }
    
        $akun = $pelanggan->akun;
    
        // Update password hanya jika password baru diberikan
        if ($request->has('password')) {
            $akun->update([
                'password' => bcrypt($request->password),
            ]);
        }
    
        $akun->update([
            'id_role' => $request->id_role
        ]);
    
        $pelanggan->update([
            'nama' => $request->nama,
            'tgl_lahir' => $request->tgl_lahir,
            'telepon' => $request->telepon,
        ]);
    
        return response()->json([
            'message' => 'Berhasil memperbarui data pelanggan',
            'data' => $pelanggan->load('akun.role')
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        $pelanggan = Pelanggan::find($id);
        if ($pelanggan->delete()) {
            return response()->json([
                'message' => 'Berhasil menghapus data pelanggan',
                'data' => $pelanggan
            ]);
        } else {
            return response()->json([
                'message' => 'Gagal menghapus data pelanggan'
            ], 500);
        }
    }
}
