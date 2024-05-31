<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Akun;
use App\Models\Pesanan;
use App\Models\StatusPesanan;
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

    /**
     * Upload the payment proof for a specific order of a customer.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @param int $id_pelanggan The ID of the customer.
     * @param int $id_pesanan The ID of the order.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the upload.
     */
    public function uploadBuktiPembayaran(Request $request, $id_pelanggan, $id_pesanan)
    {
        $validator = Validator::make($request->all(), [
            'bukti_pembayaran' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        /**
         * Retrieve a specific order for a customer.
         *
         * @param int $id_pesanan The ID of the order.
         * @param int $id_pelanggan The ID of the customer.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the retrieval.
         */
        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('id_pelanggan', $id_pelanggan)
            ->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if (!is_null($pesanan->total_dibayarkan)) {
            return response()->json(['message' => 'Pesanan sudah selesai atau sudah dibayar'], 400);
        }

        /**
         * Handle the request to upload and store the payment proof file.
         *
         * @param  \Illuminate\Http\Request  $request The HTTP request object.
         * @return void
         */
        if ($request->hasFile('bukti_pembayaran')) {
            $image = $request->file('bukti_pembayaran');
            $fileName = time() . '_' . $image->getClientOriginalName();
            $filePath = env('AZURE_STORAGE_URL') . env('AZURE_STORAGE_CONTAINER') . '/' . str_replace(' ', '%20', $image->storeAs('uploads', $fileName, 'azure'));

            $pesanan->bukti_pembayaran = $filePath;
        }

        $pesanan->save();

        /**
         * Create a new status pesanan.
         *
         * @param int $id_pesanan The ID of the order.
         * @param string $status The status of the order.
         * @return \App\Models\StatusPesanan The newly created status pesanan.
         */
        StatusPesanan::create([
            'id_pesanan' => $id_pesanan,
            'status' => 'Sudah dibayar'
        ]);

        return response()->json([
            'message' => 'Bukti pembayaran berhasil diunggah',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id_pelanggan)
    {
        $pelanggan = Pelanggan::with('id_akun')->find($id_pelanggan);
        if (is_null($pelanggan)) {
            return response()->json([
                "message" => "Pengguna tidak ditemukan",
                "data" => null
            ], 404);
        }
        $pesanan = Pesanan::where('id_pelanggan', $id_pelanggan)
            ->with([
                'pengiriman',
                'status_pesanan_latest' => function ($query) {
                    $query->select('id_status_pesanan', 'status_pesanans.id_pesanan', 'status', 'created_at');
                },
                'detail_pesanan.produk.thumbnail',
                'detail_pesanan.hampers',
            ])
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                "message" => "Tidak ada pesanan ditemukan",
                "data" => null
            ], 404);
        }

        // Calculate points for each pesanan
        foreach ($pesanan as $order) {
            $order->points = $order->calculate_poin();
        }

        return response()->json([
            "message" => "Berhasil menampilkan pesanan",
            "data" => $pesanan
        ], 200);
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
