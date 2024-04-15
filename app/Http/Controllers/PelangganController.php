<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Akun;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pelanggan = Pelanggan::with('id_akun')->get();

        if (is_null($pelanggan)) return response()->json([
            "message" => "Pengguna tidak ditemukan",
            "data" => null
        ], 404);

        return response()->json([
            "message" => "Berhasil menampilkan semua pengguna",
            "data" => $pelanggan
        ], 200);
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
    public function show(int $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('id_akun', 'history_order.detail_pesanan')->find($id_pelanggan);
        // $pesanan = Pesanan::with('detail_pesanan')->get();

        if (is_null($pelanggan)) return response()->json([
            "message" => "Pengguna tidak ditemukan",
            "data" => null
        ], 404);

        return response()->json([
            "message" => "Berhasil menampilkan pengguna",
            "data" => $pelanggan
        ], 200);

        // $pelanggan = Pelanggan::find($id_pelanggan);

        // if (!$pelanggan) {
        //     return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        // }

        // try {
        //     $pesananBelumSelesai = $pelanggan->pesananBelumSelesai()->whereNull('total_dibayarkan')->get();
        //     $historiPesanan = $pelanggan->historiPesanan()->get();

        //     return response()->json([
        //         'message' => 'Berhasil mendapatkan data pelanggan ' . $pelanggan->nama . '',
        //         'data' => [
        //             'pelanggan' => $pelanggan,
        //             'pesanan' => $pesananBelumSelesai->load('detailPesanan.produk'), // Eager load the detailPesanan and produk relationships for pending orders
        //             'histori_pesanan' => $historiPesanan,
        //         ]
        //     ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'Gagal mendapatkan data pelanggan: ' . $e->getMessage()
        //     ], 500);
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id_pelanggan)
    {
        $storeData = $request->all();

        $validator = Validator::make($storeData, [
            'id_pelanggan' => 'required|int',
            'id_akun' => 'required|int',
            'email' => 'required|email:rfc,dns|unique:akuns,email,' . $storeData['id_akun'] . ',id_akun',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        $pelanggan = Pelanggan::with('id_akun')->find($id_pelanggan);

        if (is_null($pelanggan)) return response()->json([
            "message" => "Pengguna tidak ditemukan",
            "data" => null
        ], 404);

        $akun = Akun::find($storeData['id_akun']);

        if (is_null($akun)) return response()->json([
            "message" => "Akun tidak ditemukan",
            "data" => null
        ], 404);

        $akun->email = $storeData['email'];
        $akun->profile_image = $storeData['profile_image'] ?? $akun->profile_image;
        $akun->save();

        $pelanggan->nama = $storeData['nama'] ?? $pelanggan->nama;
        $pelanggan->tgl_lahir = $storeData['tgl_lahir'] ?? $pelanggan->tgl_lahir;
        $pelanggan->telepon = $storeData['telepon'] ?? $pelanggan->telepon;
        $pelanggan->save();

        return response()->json([
            "message" => "Berhasil menampilkan pengguna",
            "data" => $pelanggan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('id_akun')->find($id_pelanggan);

        if (is_null($pelanggan)) return response()->json([
            "message" => "Pengguna tidak ditemukan",
            "data" => null
        ], 404);

        $akun = Akun::find($pelanggan->id_akun);

        if (is_null($akun)) return response()->json([
            "message" => "Akun tidak ditemukan",
            "data" => null
        ], 404);

        $akun->delete();
        $pelanggan->delete();

        return response()->json([
            "message" => "Berhasil menghapus pengguna",
            "data" => $akun
        ], 200);
    }
}
