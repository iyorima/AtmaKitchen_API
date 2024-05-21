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
use App\Models\Produk;
use App\Models\ResepProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan', 'pengiriman', 'id_metode_pembayaran'])->get();

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
        $pesananPerluDikonfirmasi = Pesanan::with(['pelanggan', 'status_pesanan', 'pengiriman', 'id_metode_pembayaran'])->get();

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
            'status' => 'diterima'
        ]);

        $totalPesanan = $pesanan->total_pesanan;
        $additionalPoints = 0;

        if ($totalPesanan >= 1000000) {
            $additionalPoints = 200;
        } elseif ($totalPesanan >= 500000) {
            $additionalPoints = 75;
        } elseif ($totalPesanan >= 100000) {
            $additionalPoints = 15;
        } elseif ($totalPesanan >= 10000) {
            $additionalPoints = 1;
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
            'status' => 'ditolak'
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
            $totalKekuranganPerBahanBakuMerged[] = [
                'id_bahan_baku' => $idBahanBaku,
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'id_metode_pembayaran' => 'required',
            'id_pelanggan' => 'required',
            'tgl_order' => 'required|date',
            'total_diskon_poin' => 'required|numeric',
            'total_dibayarkan' => 'required|numeric',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|integer',
            'produk.*.jumlah' => 'required|integer',
            'produk.*.harga' => 'required|numeric',
            'produk_hampers' => 'required|array',
            'produk_hampers.*.id_produk_hampers' => 'required|integer',
            'produk_hampers.*.jumlah' => 'required|integer',
            'produk_hampers.*.harga' => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $total_pesanan = 0;
        foreach ($data['produk'] as $produk)
            $total_pesanan += $produk['harga'] * $produk['jumlah'];
        foreach ($data['produk_hampers'] as $hampers)
            $total_pesanan += $hampers['harga'] * $hampers['jumlah'];

        $total_setelah_diskon = $total_pesanan - ($data['total_diskon_poin'] * 100);
        $total_tip = $data['total_dibayarkan'] - $total_pesanan;

        $pesanan = Pesanan::create([
            'id_metode_pembayaran' => $data['id_metode_pembayaran'],
            'id_pelanggan' => $data['id_pelanggan'],
            'tgl_order' => $data['tgl_order'],
            'total_diskon_poin' => $data['total_diskon_poin'],
            'total_pesanan' => $total_pesanan,
            'total_dibayarkan' => $data['total_dibayarkan'],
            'total_setelah_diskon' => $total_setelah_diskon,
            'total_tip' => $total_tip,
            'bukti_pembayaran' => $data['bukti_pembayaran'],
            'verified_at' => $data['verified_at'] ?? null,
            'accepted_at' => $data['accepted_at'] ?? null,
        ]);

        return response([
            'message' => 'Berhasil membuat pemesanan bahan baku baru',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pesanan $pesanan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pesanan $pesanan)
    {
        //
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
