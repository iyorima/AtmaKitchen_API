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
use App\Models\Pelanggan;
use App\Models\Pengiriman;
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
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'id_metode_pembayaran' => 'required',
            'id_pelanggan' => 'required',
            'tgl_order' => 'required|date',
            'total_diskon_poin' => 'required|numeric',
            'jenis_pengiriman' => 'required|string',
            'nama' => 'required|string',
            'telepon' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $data['produk'] = $data['produk'] ?? [];
        $data['produk_hampers'] = $data['produk_hampers'] ?? [];

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $tahun = date('y');
        $bulan = date('m');

        $nomorUrut = Pesanan::count() + 1;
        $id_pesanan = sprintf('%02d.%02d.%03d', $tahun, $bulan, $nomorUrut);

        $total_pesanan = 0;
        if (isset($data['produk']) && count($data['produk']) > 0) {
            foreach ($data['produk'] as $produk) {
                $total_pesanan += $produk['harga_jual'] * $produk['jumlah'];
            }
        }

        if (isset($data['produk_hampers']) && count($data['produk_hampers']) > 0) {
            foreach ($data['produk_hampers'] as $hampers) {
                $total_pesanan += $hampers['harga_jual'] * $hampers['jumlah'];
            }
        }

        $previous_total_poin = Poin::where('id_pelanggan', $data['id_pelanggan'])->orderBy('id_poin', 'desc')->latest()->value('total_poin') ?? 0;

        if ($data['total_diskon_poin'] > 0) {
            if ($previous_total_poin < $data['total_diskon_poin']) {
                return response(['message' => 'Poin Tidak Cukup'], 400);
            }
        }
        $total_setelah_diskon = $total_pesanan - ($data['total_diskon_poin'] * 100);

        $pesanan = Pesanan::create([
            'id_pesanan' => $id_pesanan,
            'id_metode_pembayaran' => $data['id_metode_pembayaran'],
            'id_pelanggan' => $data['id_pelanggan'],
            'tgl_order' => $data['tgl_order'],
            'total_diskon_poin' => $data['total_diskon_poin'] * 100,
            'total_pesanan' => $total_pesanan,
            'total_setelah_diskon' => $total_setelah_diskon,
            'total_tip' => 0,
            'jenis_pengiriman' => $data['jenis_pengiriman'],
            'verified_at' => $data['verified_at'] ?? null,
            'accepted_at' => $data['accepted_at'] ?? null,
        ]);

        Poin::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $data['id_pelanggan'],
            'penambahan_poin' => -$data['total_diskon_poin'],
            'total_poin' => $previous_total_poin - $data['total_diskon_poin'],
        ]);

        $previous_total_poin -= $data['total_diskon_poin'];

        if (isset($data['produk']) && count($data['produk']) > 0) {
            foreach ($data['produk'] as $produk) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => $produk['id_produk'],
                    'id_produk_hampers' => null,
                    'kategori' => 'produk',
                    'nama_produk' => $produk['nama'],
                    'harga' => $produk['harga_jual'],
                    'jumlah' => $produk['jumlah'],
                ]);
            }
        }

        if (isset($data['produk_hampers']) && count($data['produk_hampers']) > 0) {
            foreach ($data['produk_hampers'] as $hampers) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk' => null,
                    'id_produk_hampers' => $hampers['id_produk_hampers'],
                    'kategori' => 'produk_hampers',
                    'nama_produk' => $hampers['nama'],
                    'harga' => $hampers['harga_jual'],
                    'jumlah' => $hampers['jumlah'],
                ]);
            }
        }

        $poin = 0;
        if ($total_pesanan >= 1000000) {
            $poin += (int)($total_pesanan / 1000000) * 200;
            $total_pesanan %= 1000000;
        }
        if ($total_pesanan >= 500000) {
            $poin += (int)($total_pesanan / 500000) * 75;
            $total_pesanan %= 500000;
        }
        if ($total_pesanan >= 100000) {
            $poin += (int)($total_pesanan / 100000) * 15;
            $total_pesanan %= 100000;
        }
        if ($total_pesanan >= 10000) $poin += (int)($total_pesanan / 10000) * 1;

        $pelanggan = Pelanggan::find($data['id_pelanggan']);
        if ($pelanggan) {
            $tgl_order = new \DateTime($data['tgl_order']);
            $tgl_lahir = new \DateTime($pelanggan->tgl_lahir);
            $tgl_ultah_tahun_ini = new \DateTime($tgl_lahir->format('Y') . '-' . $tgl_lahir->format('m') . '-' . $tgl_lahir->format('d'));

            $batas_bawah_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('-3 days');
            $batas_atas_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('+3 days');

            if ($tgl_order >= $batas_bawah_tgl_lahir && $tgl_order <= $batas_atas_tgl_lahir) {
                $poin *= 2;
            }
        }

        $new_total_poin = $previous_total_poin + $poin;

        Poin::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $data['id_pelanggan'],
            'penambahan_poin' => $poin,
            'total_poin' => $new_total_poin,
        ]);

        if ($data['jenis_pengiriman'] == "Kurir Toko" || $data['jenis_pengiriman'] == "Kurir Ojol") {
            Pengiriman::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'nama' => $data['nama'],
                'telepon' => $data['telepon'],
                'alamat' => $data['alamat'],
            ]);
        }

        StatusPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => "Menunggu",
        ]);

        return response([
            'message' => 'Berhasil membuat pesanan',
            'data' => $pesanan
        ], 200);
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
