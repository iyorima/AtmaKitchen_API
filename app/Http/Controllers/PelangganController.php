<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Akun;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

    public function uploadBuktiPembayaran(Request $request, $id_pelanggan, $id_pesanan)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'required|file|mimes:jpg,png,pdf,jpeg|max:2048', // Sesuaikan dengan ukuran maksimum file yang diizinkan
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)->where('id_pelanggan', $id_pelanggan)->first();


        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if (!is_null($pesanan->total_dibayarkan)) {
            return response()->json(['message' => 'Pesanan sudah selesai atau sudah dibayar'], 400);
        }

        $file = $request->file('bukti_pembayaran');
        $path = $file->store('bukti_pembayaran');

        $pesanan->bukti_pembayaran = $path;
        $pesanan->save();

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diunggah',
            'path' => $path
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('akun')->find($id_pelanggan);

        if (!$pelanggan) {
            return response()->json(['message' => 'Pelanggan tidak ditemukan'], 404);
        }

        try {
            $response = [
                'message' => 'Berhasil mendapatkan data pelanggan ' . $pelanggan->nama . '',
                'data' => [
                    'pelanggan' => $pelanggan,
                    'pesanan' => [],
                    'histori_pesanan' => [],
                ]
            ];

            // Mengambil pesanan belum selesai
            $pesananBelumSelesai = $pelanggan->pesananBelumSelesai()->whereNull('total_dibayarkan')->get();
            foreach ($pesananBelumSelesai as $pesanan) {
                $response['data']['pesanan'][] = $pesanan->load('detail_pesanan.produk.thumbnail');
            }

            // Mengambil histori pesanan yang sudah selesai
            $historiPesanan = $pelanggan->historiPesanan()->get();
            foreach ($historiPesanan as $histori) {
                $response['data']['histori_pesanan'][] = $histori->load('detail_pesanan.produk.thumbnail');
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mendapatkan data pelanggan: ' . $e->getMessage()
            ], 500);
        }
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
