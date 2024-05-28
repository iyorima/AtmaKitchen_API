<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Http\Requests\StorePesananRequest;
use App\Http\Requests\UpdatePesananRequest;
use App\Models\Poin;
use App\Models\SaldoPelanggan;
use App\Models\Akun;
use App\Models\BahanBaku;
use App\Models\DetailPesanan;
use App\Models\Notifikasi;
use App\Models\Produk;
use App\Models\ResepProduk;
use App\Models\StatusPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan', 'status_pesanan_latest', 'pengiriman', 'id_metode_pembayaran'])->get();

        if (count($pesanan) > 0) {
            return response([
                "message" => "Berhasil mendapatkan semua pesanan",
                "data" => $pesanan
            ], 200);
        }

        return response([
            "message" => "pesanan tidak tersedia",
            "data" => $pesanan
        ], 200);
    }

    public function indexPesananPerluDikonfirmasi()
    {
        $pesananPerluDikonfirmasi = Pesanan::with(['pelanggan', 'status_pesanan_latest', 'pengiriman', 'id_metode_pembayaran'])
        ->whereDoesntHave('status_pesanan', function ($query) {
            $query->where('status', 'Pesanan diterima');
        })
        ->get();

        return response()->json([
            'message' => 'Daftar pesanan',
            'data' => $pesananPerluDikonfirmasi
        ]);
    }

    public function terimaPesanan(Request $request, $id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan'])
            ->findOrFail($id);
        $pesanan->status_pesanan()->update([
            'status' => 'Pesanan diterima'
        ]);

        $totalPesanan = $pesanan->total_pesanan;
        $additionalPoints = 0;
        $remainingPesanan = $totalPesanan;

        $pointsTiers = [
            1000000 => 200,
            500000 => 75,
            100000 => 15,
            10000 => 1,
        ];
        
        foreach ($pointsTiers as $threshold => $points) {
            while ($remainingPesanan >= $threshold) {
                $remainingPesanan -= $threshold;
                $additionalPoints += $points;
            }
        }

        $latestPoin = Poin::where('id_pelanggan', $pesanan->pelanggan->id_pelanggan)->latest()->first();

        $totalPoin = $latestPoin ? $latestPoin->total_poin + $additionalPoints : $additionalPoints;

        $poin = new Poin([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $pesanan->pelanggan->id_pelanggan,
            'total_poin' => $totalPoin,
            'penambahan_poin' => $additionalPoints,
        ]);

        $poin->save();



        return response()->json([
            'message' => 'Pesanan diterima',
            'data' => $pesanan, $poin
        ]);
    }

    public function tolakPesanan($id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan'])
            ->findOrFail($id);
        $pesanan->status_pesanan()->update([
            'status' => 'Pesanan ditolak'
        ]);

        // Mengembalikan stok produk
        foreach ($pesanan->detail_pesanan as $detailPesanan) {
            $produk = $detailPesanan->produk;
            $produk->kapasitas += $detailPesanan->jumlah;
            $produk->save();
        }

        $pelanggan = $pesanan->pelanggan;

        $akun = Akun::where('id_akun', $pelanggan->id_akun)->first();
        $latestSaldo = SaldoPelanggan::where('id_akun', $pesanan->pelanggan->id_akun)->latest()->first();

        // Hitung total yang dibayarkan dari pesanan
        $totalDibayarkan = $pesanan->total_dibayarkan;

        $saldoPelanggan = SaldoPelanggan::where('id_akun', $akun->id_akun)->first();

        $saldoPelanggan = $latestSaldo ? $latestSaldo->total_saldo + $totalDibayarkan : $totalDibayarkan;


        $saldoPelanggan = new SaldoPelanggan([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_akun' => $akun->id_akun,
            'id_pelanggan' => $pesanan->pelanggan->id_pelanggan,
            'total_saldo' => $saldoPelanggan,
            'saldo' => $totalDibayarkan,
        ]);

        $saldoPelanggan->save();

        //poin
        $poins = Poin::where('id_pesanan', $pesanan->id_pesanan)->get();
        foreach ($poins as $poin) {
            $poin->total_poin -= $poin->penambahan_poin;
            $poin->penambahan_poin = 0;
            $poin->save();
        }

        return response()->json([
            'message' => 'Pesanan ditolak',
            'data' => $pesanan, $poins, $saldoPelanggan
        ]);
    }

    public function listBahanBakuPerluDibeli($id) //menampilkan bahan baku yang perlu dibeli per produk dan total yang diperlukan
    {
        $pesanan = Pesanan::findOrFail($id);
        $listBahanBakuPerluDibeli = []; //init
        $totalKekuranganPerBahanBaku = []; //init

        $detailPesanan = DetailPesanan::where('id_pesanan', $pesanan->id_pesanan)->get();
        foreach ($detailPesanan as $detail) {
            $produk = Produk::findOrFail($detail->id_produk);
            $resepProduk = ResepProduk::where('id_produk', $produk->id_produk)->get();

            // init
            $dataProduk = [
                'id_produk' => $produk->id_produk,
                'stok' => $produk->kapasitas,
                'total_dibutuhkan' => 0,
                'total_kekurangan' => 0,
                'bahan_baku_perlu_dibeli' => []
            ];

            if (!$resepProduk->isEmpty()) {
                foreach ($resepProduk as $resep) {
                    $bahanBaku = BahanBaku::findOrFail($resep->id_bahan_baku);
                    $stok = $bahanBaku->stok;
                    $jumlahBahanBakuDibutuhkan = $resep->jumlah * $detail->jumlah;
                    if ($stok < $jumlahBahanBakuDibutuhkan) {
                        $dataProduk['total_dibutuhkan'] += $jumlahBahanBakuDibutuhkan;
                        $kekurangan = $jumlahBahanBakuDibutuhkan - $stok;
                        $dataProduk['total_kekurangan'] += $kekurangan;
                        //data yang bakal di push ke array
                        $dataBahanBaku = [
                            'id_bahan_baku' => $bahanBaku->id_bahan_baku,
                            'nama_bahan_baku' => $bahanBaku->nama,
                            'total_kekurangan' => $kekurangan
                        ];
                        $dataProduk['bahan_baku_perlu_dibeli'][] = $dataBahanBaku; //data yg perlu di beli per produk

                        if (!isset($totalKekuranganPerBahanBaku[$bahanBaku->id_bahan_baku])) {
                            $totalKekuranganPerBahanBaku[$bahanBaku->id_bahan_baku] = 0;
                        }
                        $totalKekuranganPerBahanBaku[$bahanBaku->id_bahan_baku] += $kekurangan;
                    }
                }
            }

            if ($dataProduk['total_kekurangan'] > 0) {
                $listBahanBakuPerluDibeli[] = $dataProduk;
            }
        }

        //gabung semua biar tau total kekurangan berapa
        $totalKekuranganPerBahanBakuMerged = [];
        foreach ($totalKekuranganPerBahanBaku as $idBahanBaku => $totalKekurangan) {
            $bahanBaku = BahanBaku::findOrFail($idBahanBaku);
            $totalKekuranganPerBahanBakuMerged[] = [
                'id_bahan_baku' => $idBahanBaku,
                'nama_bahan_baku' => $bahanBaku->nama,
                'total_kekurangan' => $totalKekurangan
            ];
        }

        if (empty($listBahanBakuPerluDibeli)) { //kalau gada yang kurang
            return response()->json([
                'message' => 'Tidak ada bahan baku yang perlu dibeli untuk pesanan ini',
                'data' => [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nama_pelanggan' => $pesanan->pelanggan->nama,
                    'detail_pesanan' => $pesanan->detail_pesanan
                ]
            ]);
        } else {
            return response()->json([
                'message' => 'List bahan baku yang perlu dibeli',
                'data' => [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'nama_pelanggan' => $pesanan->pelanggan->nama,
                    'list_produk_dan_bahan_baku' => $listBahanBakuPerluDibeli,
                    'total_kekurangan_per_bahan_baku' => $totalKekuranganPerBahanBakuMerged
                ]
            ]);
        }
    }

    // @Nathan
    public function getAllPesananNeedConfirmDelivery()
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran'
        ])->where('jenis_pengiriman', "!=", "Ambil Sendiri")->orderBy('id_pesanan', 'desc')->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan',
            'data' => $pesanan
        ], 200);
    }

    // @Nathan
    public function getAllPesananNeedConfirmPayment()
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran'
        ])->where('total_dibayarkan', null)->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan',
            'data' => $pesanan
        ], 200);
    }

    // @Nathan
    public function createAcceptedPayment(Request $request, string $id_pesanan)
    {
        $pesanan = Pesanan::with('pengiriman:id_pesanan,harga,jarak')->find($id_pesanan);

        if (is_null($pesanan)) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'total_dibayarkan' => 'required|int|min:' . $pesanan->total_setelah_diskon + $pesanan->pengiriman->harga,
            // 'id_karyawan' => 'required'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $status = StatusPesanan::create([
            'id_pesanan' => $id_pesanan,
            // 'id_karyawan' => $updateData['id_karyawan'],
            'status' => "Pembayaran diterima"
        ]);

        $totalTip = $updateData['total_dibayarkan'] - ($pesanan->total_setelah_diskon + $pesanan->pengiriman->harga);

        if ($totalTip > 0) {
            $updateData['total_tip'] = $totalTip;
        } else {
            $updateData['total_tip'] = 0;
        }

        if ($pesanan->update($updateData) && $status) {
            return response()->json([
                'message' => 'Pembayaran berhasil dikonfirmasi',
                'data' => $pesanan
            ], 200);
        }

        return response()->json([
            'message' => 'Pembayaran gagal dikonfirmasi',
            'data' => null
        ], 404);
    }

    // @Nathan
    public function pesananAcceptedByCustomer(string $id_pesanan)
    {
        $pesanan = Pesanan::with('pelanggan.akun')->find($id_pesanan);

        if (is_null($pesanan)) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        if (!is_null($pesanan->accepted_at)) {
            return response()->json([
                'message' => 'Pesanan telah diterima',
                'data' => null
            ], 404);
        }

        $status = StatusPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => 'Selesai'
        ]);

        $notification = Notifikasi::create([
            'judul' => 'Pesanan Selesai',
            'deskripsi' => 'Hai, Pesanan Anda [' . $id_pesanan . '] telah diterima.',
            'id_akun' => $pesanan->pelanggan->akun->id_akun
        ]);

        $updateData = [
            'accepted_at' => now()
        ];

        if ($pesanan->update($updateData) && $status && $notification) {
            return response()->json([
                'message' => 'Pesanan berhasil diterima',
                'data' => $pesanan
            ], 200);
        }

        return response()->json([
            'message' => 'Pesanan gagal diterima',
            'data' => null
        ], 404);
    }

    // @Nathan
    public function getAllPesananInProcess()
    {
        $pesanan = Pesanan::with([
            'status_pesanan_latest',
            'pelanggan',
            'id_metode_pembayaran'
        ])->where('verified_at', "!=", null)->where('accepted_at', null)->whereHas('status_pesanan_latest', function ($query) {
            return $query->where('status', 'Pesanan diproses');
        })->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function getAllPesananRejected()
    {
        $pesanan = Pesanan::with([
            'status_pesanan_latest',
            'pelanggan',
            'id_metode_pembayaran'
        ])->where('verified_at', "!=", null)->where('accepted_at', null)->whereHas('status_pesanan_latest', function ($query) {
            return $query->where('status', 'Pesanan ditolak');
        })->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan',
            'data' => $pesanan
        ], 200);
    }

    public function getAllPesananPaymentVerified()
    {
        $pesanan = Pesanan::with([
            'status_pesanan_latest',
            'pelanggan',
            'id_metode_pembayaran'
        ])->where('verified_at', "!=", null)->where('accepted_at', null)->whereHas('status_pesanan_latest', function ($query) {
            return $query->where('status', 'Pembayaran diterima');
        })->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan',
            'data' => $pesanan
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePesananRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('pelanggan', 'pengiriman')->find($id);

        if (is_null($pesanan)) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan pesanan',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePesananRequest $request, Pesanan $pesanan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pesanan $pesanan)
    {
        //
    }
}
