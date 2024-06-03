<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
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
use App\Models\Keranjang;
use App\Models\DetailKeranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class PesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan_latest', 'pengiriman', 'id_metode_pembayaran', 'detail_pesanan'])->orderBy('id_pesanan', 'desc')->get();

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
        $pesananPerluDikonfirmasi = Pesanan::with(['pelanggan', 'status_pesanan', 'pengiriman', 'id_metode_pembayaran'])
            ->whereDoesntHave('status_pesanan', function ($query) {
                $query->where('status', 'Diterima');
            })
            ->get();

        return response()->json([
            'message' => 'Daftar pesanan',
            'data' => $pesananPerluDikonfirmasi
        ]);
    }

    public function terimaPesanan($id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan'])
            ->findOrFail($id);
        StatusPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => "Diterima"
        ]);

        $previous_total_poin = Poin::where('id_pelanggan', $pesanan->id_pelanggan)
            ->orderBy('id_poin', 'desc')
            ->latest()
            ->value('total_poin') ?? 0;

        $poin = 0;
        if ($pesanan->total_pesanan >= 1000000) {
            $poin += (int)($pesanan->total_pesanan / 1000000) * 200;
            $pesanan->total_pesanan %= 1000000;
        }
        if ($pesanan->total_pesanan >= 500000) {
            $poin += (int)($pesanan->total_pesanan / 500000) * 75;
            $pesanan->total_pesanan %= 500000;
        }
        if ($pesanan->total_pesanan >= 100000) {
            $poin += (int)($pesanan->total_pesanan / 100000) * 15;
            $pesanan->total_pesanan %= 100000;
        }
        if ($pesanan->total_pesanan >= 10000) $poin += (int)($pesanan->total_pesanan / 10000) * 1;

        $pelanggan = Pelanggan::find($pesanan->id_pelanggan);

        if ($pelanggan) {
            $tgl_order = new \DateTime($pesanan->tgl_order);
            $tgl_lahir = new \DateTime($pelanggan->tgl_lahir);

            $tgl_ultah_tahun_ini = new \DateTime($tgl_order->format('Y') . '-' . $tgl_lahir->format('m') . '-' . $tgl_lahir->format('d'));

            $batas_bawah_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('-3 days');
            $batas_atas_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('+3 days');

            if ($tgl_order >= $batas_bawah_tgl_lahir && $tgl_order <= $batas_atas_tgl_lahir) {
                $poin *= 2;
            }
        }

        $new_total_poin = $previous_total_poin + $poin;

        $poin_new = Poin::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $pesanan->id_pelanggan,
            'penambahan_poin' => $poin,
            'total_poin' => $new_total_poin,
        ]);

        return response()->json([
            'message' => 'Pesanan diterima',
            'data' => $pesanan, $poin_new
        ]);
    }

    public function tolakPesanan($id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'status_pesanan'])
            ->findOrFail($id);
        StatusPesanan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'status' => "Ditolak"
        ]);

        $pelanggan = $pesanan->pelanggan;

        $akun = Akun::where('id_akun', $pelanggan->id_akun)->first();

        $saldoPelanggan = SaldoPelanggan::where('id_akun', $akun->id_akun)
            ->orderBy('id_akun', 'desc')
            ->latest()
            ->value('total_saldo') ?? 0;;

        SaldoPelanggan::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_akun' => $akun->id_akun,
            'total_saldo' => $pesanan->total_setelah_diskon + $saldoPelanggan,
            'saldo' => $pesanan->total_setelah_diskon,
        ]);

        //poin
        $previous_total_poin = Poin::where('id_pelanggan', $pesanan->id_pelanggan)
            ->orderBy('id_poin', 'desc')
            ->latest()
            ->value('total_poin') ?? 0;

        Poin::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $pesanan->id_pelanggan,
            'penambahan_poin' => ($pesanan->total_diskon_poin / 100),
            'total_poin' => $previous_total_poin + ($pesanan->total_diskon_poin / 100),
        ]);

        return response()->json([
            'message' => 'Pesanan ditolak',
            'data' => $pesanan, $saldoPelanggan
        ]);
    }

    /**
     * Process a Pesanan by its ID.
     *
     * @param string $id_pesanan The ID of the Pesanan to process.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the process.
     */
    public function processPesananByIdPesanan(string $id_pesanan)
    {
        /**
         * Retrieve a specific Pesanan with related data.
         *
         * @param int $id_pesanan The ID of the Pesanan to retrieve.
         * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null The Pesanan model with related data, or null if not found.
         */
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest' => function ($query) {
                $query->where('status', 'Diterima');
            }
        ])
            ->findOrFail($id_pesanan);

        /**
         * Check if the pesanan exists and return the appropriate response.
         *
         * @param  \App\Pesanan  $pesanan The Pesanan model.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the check.
         */
        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 404);
        }

        /**
         * Check if the pesanan has the latest status and return the appropriate response.
         *
         * @param  \App\Pesanan  $pesanan The Pesanan model.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the check.
         */
        if ($pesanan->status_pesanan_latest->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan harus diterima terlebih dahulu',
                'data' => $pesanan
            ]);
        }

        /**
         * Create a new status pesanan record.
         *
         * @param int $id_pesanan The ID of the pesanan.
         * @param string $status The status of the pesanan.
         * @return \App\Models\StatusPesanan The newly created status pesanan record.
         */
        StatusPesanan::create([
            'id_pesanan' => $id_pesanan,
            'status' => 'Diproses'
        ]);

        return response()->json([
            'message' => 'Status pesanan berhasil diubah menjadi `Diproses`',
            'data' => $pesanan
        ]);
    }

    /**
     * Display the list of raw materials that need to be purchased per product and the total required.
     *
     * @param  int  $id  The ID of the order.
     * @return \Illuminate\Http\JsonResponse
     */
    public function listBahanBakuPerluDibeli($id)
    {
        // Common part of the query used in both subQuery and mainQuery
        $commonJoin = function ($join) {
            $join->on('p.id_produk', '=', 'rp.id_produk')
                ->orWhere(function ($query) {
                    $query->where('p.ukuran', '10x20 cm')
                        ->whereIn('rp.id_produk', function ($subQuery) {
                            $subQuery->select('p2.id_produk')
                                ->from('produks as p2')
                                ->where('p2.ukuran', '20x20 cm')
                                ->whereColumn('p2.nama', 'p.nama');
                        });
                });
        };

        // subQuery part
        $subQuery = DB::table('detail_pesanans as dp')
            ->join('produk_hampers as ph', 'dp.id_produk_hampers', '=', 'ph.id_produk_hampers')
            ->join('detail_hampers as dh', 'ph.id_produk_hampers', '=', 'dh.id_produk_hampers')
            ->join('produks as p', 'dh.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(CEIL(dp.jumlah/2) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // mainQuery part
        $mainQuery = DB::table('detail_pesanans as dp')
            ->join('produks as p', 'dp.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(IF(p.ukuran = "10x20 cm", CEIL(dp.jumlah/2), dp.jumlah) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // Union all subQuery and mainQuery
        $unionQuery = $mainQuery->unionAll($subQuery);

        // Final query to show only bahan baku where usage > stok
        $bahanBakuUsage = DB::table(DB::raw("({$unionQuery->toSql()}) as combinedQuery"))
            ->mergeBindings($unionQuery) // Bindings are necessary for subqueries
            ->join('bahan_bakus as bb', 'combinedQuery.nama', '=', 'bb.nama')
            ->select('combinedQuery.id_pesanan', 'combinedQuery.nama', DB::raw('SUM(combinedQuery.total) as total'), 'bb.stok', 'combinedQuery.satuan')
            ->groupBy('combinedQuery.id_pesanan', 'combinedQuery.nama', 'combinedQuery.satuan', 'bb.stok')
            ->havingRaw('SUM(combinedQuery.total) > bb.stok')
            ->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan penggunaan bahan baku',
            'data' => $bahanBakuUsage
        ], 200);
    }


    // @Nathan
    /**
     * Create and confirm payment for a pesanan (order).
     *
     * @param \Illuminate\Http\Request $request The HTTP request object.
     * @param string $id_pesanan The ID of the pesanan to create and confirm payment for.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the payment confirmation.
     */
    public function createConfirmPayment(Request $request, string $id_pesanan)
    {
        /**
         * Retrieve a specific pesanan with its associated pengiriman.
         *
         * @param int $id_pesanan The ID of the pesanan to retrieve.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the pesanan data.
         */
        $pesanan = Pesanan::with('pengiriman:id_pesanan,harga,jarak')
            ->find($id_pesanan);

        if (is_null($pesanan)) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => null
            ], 404);
        }

        /**
         * Update the data of a Pesanan (Order) based on the given request.
         *
         * @param  \Illuminate\Http\Request  $request The HTTP request object.
         * @return \Illuminate\Http\Response The HTTP response object.
         */
        $updateData = $request->all();

        $biaya_kirim = isset($pesanan->pengiriman->harga) ? $pesanan->pengiriman->harga : 0;

        $validate = Validator::make($updateData, [
            'total_dibayarkan' => 'required|int|min:' . $pesanan->total_setelah_diskon +
                $biaya_kirim,
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        /**
         * Create a new status pesanan.
         *
         * @param int $id_pesanan The ID of the pesanan.
         * @param string $status The status of the pesanan.
         * @return \App\Models\StatusPesanan The newly created status pesanan.
         */
        $status = StatusPesanan::create([
            'id_pesanan' => $id_pesanan,
            'status' => "Pembayaran valid"
        ]);

        /**
         * Calculate and update the total tip for a pesanan (order).
         *
         * @param array $updateData The updated data for the pesanan.
         * @param Pesanan $pesanan The pesanan object.
         * @return void
         */
        $totalTip = $updateData['total_dibayarkan'] - ($pesanan->total_setelah_diskon + isset($pesanan->pengiriman->harga) ? $pesanan->pengiriman->harga : 0);

        if ($totalTip > 0) {
            $updateData['total_tip'] = $totalTip;
        } else {
            $updateData['total_tip'] = 0;
        }

        /**
         * Update the verification status of a pesanan and return a JSON response.
         *
         * @param  \Illuminate\Http\Request  $request The HTTP request object.
         * @param  \App\Models\Pesanan  $pesanan The pesanan object.
         * @return \Illuminate\Http\JsonResponse The JSON response containing the result of the update.
         */
        $updateData['verified_at'] = now();

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
    /**
     * Get all pesanan that need to confirm delivery.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPesananNeedConfirmDelivery()
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran',
            'detail_pesanan'
        ])->where('jenis_pengiriman', '!=', 'Ambil Sendiri')
            ->whereHas('status_pesanan_latest', function ($query) {
                return $query->where('status', 'Menunggu ongkir');
            })
            ->orderBy('id_pesanan', 'desc')->get();

        if ($pesanan->isEmpty()) {
            return response()->json([
                'message' => 'Pesanan tidak tersedia',
                'data' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan pesanan',
            'data' => $pesanan
        ], 200);
    }

    // @Nathan
    // Status: Sudah dibayar tapi belum di konfirmasi admin
    /**
     * Get all pesanan that need to confirm payment.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPesananNeedConfirmPayment()
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran',
            'detail_pesanan'
        ])
            ->whereHas('status_pesanan_latest', function ($query) {
                return $query->where('status', 'Sudah dibayar');
            })
            ->orderBy('id_pesanan', 'desc')->get();

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
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran',
            'detail_pesanan'
        ])
            ->whereHas('status_pesanan_latest', function ($query) {
                return $query->where('status', 'Ditolak');
            })
            ->orderBy('id_pesanan', 'desc')->get();

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
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran',
            'detail_pesanan'
        ])
            ->whereHas('status_pesanan_latest', function ($query) {
                return $query->where('status', 'Pembayaran valid');
            })
            ->orderBy('id_pesanan', 'desc')->get();

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

    // @Nathan Week 3
    /** 
     * Menampilkan daftar pesanan yang sedang diproses (Admin)
     **/
    public function getAllPesananInProcess()
    {
        $pesanan = Pesanan::with([
            'pelanggan',
            'status_pesanan_latest',
            'pengiriman',
            'id_metode_pembayaran',
            'detail_pesanan'
        ])
            ->whereHas('status_pesanan_latest', function ($query) {
                return $query->where('status', 'Diproses');
            })
            ->orderBy('id_pesanan', 'desc')->get();

        if ($pesanan->isEmpty()) {
            return response()->noContent();
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan yang sedang diproses',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Get all pesanan with late payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPesananLatePayment()
    {
        $pesanan = Pesanan::with([
            'status_pesanan_latest',
            'pelanggan',
            'id_metode_pembayaran'
        ])->where('accepted_at', null)->whereHas('status_pesanan_latest', function ($query) {
            return $query->select('id_status_pesanan', 'status')->where('status', 'Dibatalkan otomatis');
        })->get();

        if ($pesanan->isEmpty()) {
            return response()->noContent();
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pesanan yang sedang diproses',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Get the usage of raw materials by period.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBahanBakuUsageByPeriod(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        // Subquery to handle the specific case where ukuran is 10x20 cm
        $subQuery = DB::table('detail_pesanans as dp')
            ->join('produk_hampers as ph', 'dp.id_produk_hampers', '=', 'ph.id_produk_hampers')
            ->join('detail_hampers as dh', 'ph.id_produk_hampers', '=', 'dh.id_produk_hampers')
            ->join('produks as p', 'dh.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', function ($join) {
                $join->on('p.id_produk', '=', 'rp.id_produk')
                    ->orWhere(function ($query) {
                        $query->where('p.ukuran', '10x20 cm')
                            ->whereIn('rp.id_produk', function ($subQuery) {
                                $subQuery->select('p2.id_produk')
                                    ->from('produks as p2')
                                    ->where('p2.ukuran', '20x20 cm')
                                    ->whereColumn('p2.nama', 'p.nama');
                            });
                    });
            })
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('bb.nama', DB::raw('SUM(CEIL(dp.jumlah/2) * rp.jumlah) as total'), 'bb.satuan')
            ->whereBetween('dp.created_at', [$start_date, $end_date])
            ->groupBy('bb.nama', 'bb.satuan');

        $bahanBakuUsage = DB::table('detail_pesanans as dp')
            ->join('produks as p', 'dp.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', function ($join) {
                $join->on('p.id_produk', '=', 'rp.id_produk')
                    ->orWhere(function ($query) {
                        $query->where('p.ukuran', '10x20 cm')
                            ->whereIn('rp.id_produk', function ($subQuery) {
                                $subQuery->select('p2.id_produk')
                                    ->from('produks as p2')
                                    ->where('p2.ukuran', '20x20 cm')
                                    ->whereColumn('p2.nama', 'p.nama');
                            });
                    });
            })
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->whereBetween('dp.created_at', [$start_date, $end_date])
            ->select('bb.nama', DB::raw('SUM(IF(p.ukuran = "10x20 cm", CEIL(dp.jumlah/2), dp.jumlah) * rp.jumlah) as total'), 'bb.satuan')
            ->groupBy('bb.nama', 'bb.satuan')
            ->unionAll($subQuery)
            ->get();

        // Group by nama and sum the totals
        $groupedBahanBakuUsage = [];
        foreach ($bahanBakuUsage as $item) {
            if (isset($groupedBahanBakuUsage[$item->nama])) {
                $groupedBahanBakuUsage[$item->nama]['total'] += $item->total;
            } else {
                $groupedBahanBakuUsage[$item->nama] = [
                    'total' => $item->total,
                    'satuan' => $item->satuan
                ];
            }
        }

        // Convert to the desired output format
        $result = [];
        foreach ($groupedBahanBakuUsage as $nama => $data) {
            $result[] = [
                'nama' => $nama,
                'total' => $data['total'],
                'satuan' => $data['satuan']
            ];
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan penggunaan bahan baku',
            'data' => $result
        ], 200);
    }

    public function getBahanBakuUsageOfHampersByPeriod(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        // $bahanBakuUsage = DB::table('detail_pesanans')
        //     ->join('produk_hampers', 'detail_pesanans.id_produk_hampers', '=', 'produk_hampers.id_produk_hampers')
        //     ->join('detail_hampers', 'produk_hampers.id_produk_hampers', '=', 'detail_hampers.id_produk_hampers')
        //     ->join('produks', 'detail_hampers.id_produk', '=', 'produks.id_produk')
        //     ->join('resep_produks', 'produks.id_produk', '=', 'resep_produks.id_produk')
        //     ->join('bahan_bakus', 'resep_produks.id_bahan_baku', '=', 'bahan_bakus.id_bahan_baku')
        //     ->select('bahan_bakus.nama', DB::raw('SUM(detail_pesanans.jumlah * resep_produks.jumlah) as total'))
        //     ->whereBetween('detail_hampers.created_at', [$start_date, $end_date])
        //     ->groupBy('bahan_bakus.nama')
        //     ->unionAll(function ($query) use ($start_date, $end_date) {
        //         $query->select('bahan_bakus.nama', DB::raw('SUM(detail_pesanans.jumlah * resep_produks.jumlah) as total'))
        //             ->from('detail_pesanans')
        //             ->join('produk_hampers', 'detail_pesanans.id_produk_hampers', '=', 'produk_hampers.id_produk_hampers')
        //             ->join('detail_hampers', 'produk_hampers.id_produk_hampers', '=', 'detail_hampers.id_produk_hampers')
        //             ->join('produks', 'detail_hampers.id_produk', '=', 'produks.id_produk')
        //             ->join('resep_produks', 'produks.id_produk', '=', 'resep_produks.id_produk')
        //             ->join('bahan_bakus', 'resep_produks.id_bahan_baku', '=', 'bahan_bakus.id_bahan_baku')
        //             ->whereBetween('detail_pesanans.created_at', [$start_date, $end_date])
        //             ->orWhere(function ($query) {
        //                 $query->where('produks.ukuran', '10x20 cm')
        //                     ->whereIn('produks.nama', function ($subquery) {
        //                         $subquery->select('produks.nama')
        //                             ->from('produks')
        //                             ->where('produks.ukuran', '20x20 cm');
        //                     });
        //             })
        //             ->groupBy('bahan_bakus.nama');
        //     })
        //     ->get();

        $bahanBakuUsage = DB::table('detail_pesanans as dp')
            ->join('produk_hampers as ph', 'dp.id_produk_hampers', '=', 'ph.id_produk_hampers')
            ->join('detail_hampers as dh', 'ph.id_produk_hampers', '=', 'dh.id_produk_hampers')
            ->join('produks as p', 'dh.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', function ($join) {
                $join->on('p.id_produk', '=', 'rp.id_produk')
                    ->orWhere(function ($query) {
                        $query->where('p.ukuran', '10x20 cm')
                            ->whereIn('rp.id_produk', function ($subQuery) {
                                $subQuery->select('p2.id_produk')
                                    ->from('produks as p2')
                                    ->where('p2.ukuran', '20x20 cm')
                                    ->whereColumn('p2.nama', 'p.nama');
                            });
                    });
            })
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('bb.nama', DB::raw('SUM(dp.jumlah * rp.jumlah) as total'))
            ->whereBetween('dp.created_at', [$start_date, $end_date])
            ->groupBy('bb.nama')->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan penggunaan bahan baku hampers',
            'data' => $bahanBakuUsage
        ], 200);
    }

    /**
     * Automatically updates the status of orders to "Pesanan dibatalkan" after 2 days
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoUpdateStatueAfter2Days()
    {
        $expired = Carbon::now()->subDays(2);

        $pesanan = Pesanan::with('status_pesanan_latest')
            ->whereHas('status_pesanan_latest', function ($query) {
                $query->where('status', 'Menunggu ongkir');
            })
            ->where('tgl_order', '<', $expired)
            ->get();

        foreach ($pesanan as $p) {
            StatusPesanan::create([
                'id_pesanan' => $p->id_pesanan,
                'status' => 'Dibatalkan otomatis'
            ]);
        }

        return response()->json([
            'data' => $pesanan
        ]);
    }

    /**
     * Update the status of a pesanan (order).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id_pesanan
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusPesanan(Request $request, string $id_pesanan)
    {
        $pesanan = Pesanan::find($id_pesanan);

        if (!$pesanan) {
            return response()->json([
                'message' => 'Pesanan tidak ditemukan',
                'data' => null
            ], 200);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'status' => 'required|in:Siap dipickup,Sedang dikirim kurir,Sudah dipickup,Selesai,Diterima,Diproses,Ditolak'
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $status = StatusPesanan::create([
            'id_pesanan' => $id_pesanan,
            'status' => $data['status']
        ]);

        if ($data['status'] === 'Selesai') {
            $pesanan->accepted_at = now();
            $pesanan->save();

            return response()->json([
                'message' => 'Pesanan diterima',
                'data' => $status
            ], 200);
        }

        if ($status) {
            return response()->json([
                'message' => 'Berhasil mengubah status pesanan',
                'data' => $status
            ], 200);
        }

        return response()->json([
            'message' => 'Gagal mengubah status pesanan',
            'data' => null
        ], 404);
    }

    // @Nathan
    public function getAllPendapatanBulanan()
    {
        $currentYear = date('Y');

        // All months in a year
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        // Fetching data from the database
        $data = Pesanan::select([
            DB::raw('MONTHNAME(tgl_order) as month'),
            DB::raw('count(*) as total_orders'),
            DB::raw('sum(total_setelah_diskon + COALESCE(pengirimen.harga, 0)) as total_sales'),
        ])
            ->join('pengirimen', 'pesanans.id_pesanan', '=', 'pengirimen.id_pesanan')
            ->whereYear('tgl_order', $currentYear)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get()
            ->keyBy('month');

        // Initialize an array with all months set to 0 orders and 0 sales
        $result = [];
        foreach ($months as $month) {
            $result[] = [
                'month' => $month,
                'total_orders' => $data->has($month) ? $data[$month]->total_orders : 0,
                'total_sales' => $data->has($month) ? $data[$month]->total_sales : 0,
            ];
        }

        return response()->json([
            'message' => 'Berhasil mendapatkan seluruh pendapatan tahun ' . $currentYear,
            'data' => $result
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
            'jenis_pengiriman' => 'required|string|in:Kurir Toko,Kurir Ojol,Ambil Sendiri',
            'nama' => 'string',
            'telepon' => 'string',
            'alamat' => 'string',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $data['produk'] = $data['produk'] ?? [];
        $data['produk_hampers'] = $data['produk_hampers'] ?? [];

        /**
         * Generate a unique ID for a new order.
         *
         * This function generates a unique ID for a new order by combining the current year, month, and a sequential number.
         *
         * @return string The generated unique ID for the new order.
         */
        $tahun = date('y');
        $bulan = date('m');

        $nomorUrut = Pesanan::count() + 1;
        $id_pesanan = sprintf('%02d.%02d.%d', $tahun, $bulan, $nomorUrut);

        /**
         * Calculate the total pesanan based on the provided data.
         *
         * @param array $data The data containing the produk and produk_hampers information.
         * @return int The total pesanan value.
         */
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


        /**
         * Calculate the previous total points of a customer and check if it is sufficient for the discount.
         *
         * @param array $data The data containing the customer ID and the total discount points.
         * @return \Illuminate\Http\JsonResponse The JSON response indicating if the points are sufficient or not.
         */
        $previous_total_poin = Poin::where('id_pelanggan', $data['id_pelanggan'])
            ->orderBy('id_poin', 'desc')
            ->latest()
            ->value('total_poin') ?? 0;

        if ($data['total_diskon_poin'] > 0) {
            if ($previous_total_poin < $data['total_diskon_poin']) {
                return response()->json(['message' => 'Poin tidak mencukupi'], 400);
            }
        }

        /**
         * Calculate the total after discount based on the provided data.
         *
         * @param array $data The data containing the total pesanan and the total discount points.
         * @return int The total after discount value.
         */
        $total_setelah_diskon = $total_pesanan - ($data['total_diskon_poin'] * 100);

        /**
         * Create a new order based on the provided data.
         *
         * @param array $data The data containing the order information.
         * @return \Illuminate\Http\JsonResponse The JSON response indicating the success or failure of the operation.
         */
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
            'verified_at' => null,
            'accepted_at' => null,
        ]);


        /**
         * Create a new Poin record.
         *
         * @param  int  $id_pesanan  The ID of the pesanan.
         * @param  int  $id_pelanggan  The ID of the pelanggan.
         * @param  int  $penambahan_poin  The amount of poin to be added (negative value for deduction).
         * @param  int  $total_poin  The updated total poin after deduction.
         * @return void
         */
        Poin::create([
            'id_pesanan' => $pesanan->id_pesanan,
            'id_pelanggan' => $data['id_pelanggan'],
            'penambahan_poin' => -$data['total_diskon_poin'],
            'total_poin' => $previous_total_poin - $data['total_diskon_poin'],
        ]);

        $previous_total_poin -= $data['total_diskon_poin'];

        /**
         * Create detail pesanan for each produk in the data array.
         *
         * @param array $data The data array containing the produk information.
         * @param Pesanan $pesanan The pesanan object.
         * @return void
         */
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

        /**
         * Create detail pesanan for each produk hampers in the data array.
         *
         * @param array $data The data array containing the produk hampers information.
         * @param Pesanan $pesanan The pesanan object.
         * @return void
         */
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

        $pelanggan = Pelanggan::find($data['id_pelanggan']);

        if ($data['jenis_pengiriman'] == "Kurir Toko" || $data['jenis_pengiriman'] == "Kurir Ojol") {
            Pengiriman::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'nama' => $data['nama'],
                'telepon' => $data['telepon'],
                'alamat' => $data['alamat'],
            ]);

            StatusPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'status' => "Menunggu ongkir",
            ]);
        } else {
            StatusPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'status' => "Menunggu pembayaran",
            ]);
        }

        /**
         * Retrieve the cart for the specified customer and check if it exists.
         *
         * @param  \App\Models\Pelanggan  $pelanggan
         * @return \Illuminate\Http\Response
         */
        $keranjang = Keranjang::where('id_pelanggan', $pelanggan->id_pelanggan)->first();

        if (is_null($keranjang)) {
            return response([
                'message' => 'Keranjang tidak ditemukan',
                'data' => null,
            ], 404);
        }

        /**
         * Delete all detail keranjang associated with a keranjang.
         *
         * @param  \App\Models\Keranjang  $keranjang
         * @return \Illuminate\Http\Response
         */
        $detail_keranjang = DetailKeranjang::where('id_keranjang', $keranjang->id_keranjang)->get();

        if ($detail_keranjang->isEmpty()) {
            return response([
                'message' => 'Tidak ada detail keranjang untuk dihapus',
                'data' => null,
            ], 404);
        }

        $detail_keranjang->each->delete();

        return response([
            'message' => 'Pesanan berhasil dibuat',
            'data' => $pesanan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pesanan = Pesanan::with('pelanggan', 'pengiriman', 'detail_pesanan')->find($id);

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

    public function showByMonth($date)
    {
        // Extract the year and month from the provided date
        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));

        // Fetch data from the database
        $data = Pesanan::select([
            'produks.nama as produk_name',
            'produks.harga_jual as produk_price',
            DB::raw('SUM(detail_pesanans.jumlah * detail_pesanans.harga) as total_income'),
            DB::raw('SUM(detail_pesanans.jumlah) as total_quantity')
        ])
            ->join('detail_pesanans', 'pesanans.id_pesanan', '=', 'detail_pesanans.id_pesanan')
            ->join('produks', 'detail_pesanans.id_produk', '=', 'produks.id_produk')
            ->whereYear('pesanans.tgl_order', $year)
            ->whereMonth('pesanans.tgl_order', $month)
            ->groupBy('produks.nama', 'produks.harga_jual')
            ->get();

        // Structure the response data
        $result = $data->map(function ($item) {
            return [
                'produk' => $item->produk_name,
                'kuantitas' => $item->total_quantity,
                'harga' => $item->produk_price,
                'jumlah_uang' => $item->total_income,
            ];
        });

        return response()->json([
            'message' => 'Berhasil mendapatkan pesanan bulan ' . date('F', strtotime($date)) . ' ' . $year,
            'data' => $result
        ], 200);
    }

    public function showToday()
    {
        $date = date('Y-m-d', strtotime('-1 day'));

        $data = Pesanan::with([
            'pelanggan',
            'pengiriman',
            'id_metode_pembayaran',
            'status_pesanan_latest',
            'detail_pesanan' => function ($query) {
                $query->with([
                    'hampers',
                    'produk.thumbnail'
                ]);
            }
        ])
            ->where('tgl_order', $date)
            ->whereHas('status_pesanan_latest', function ($query) {
                $query->where('status', 'Diterima');
            })
            ->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan pesanan yang harus diproses hari ini',
            'data' => $data
        ], 200);
    }

    public function showBahanBakuUsage($id)
    {
        // Common part of the query used in both subQuery and mainQuery
        $commonJoin = function ($join) {
            $join->on('p.id_produk', '=', 'rp.id_produk')
                ->orWhere(function ($query) {
                    $query->where('p.ukuran', '10x20 cm')
                        ->whereIn('rp.id_produk', function ($subQuery) {
                            $subQuery->select('p2.id_produk')
                                ->from('produks as p2')
                                ->where('p2.ukuran', '20x20 cm')
                                ->whereColumn('p2.nama', 'p.nama');
                        });
                });
        };

        // subQuery part
        $subQuery = DB::table('detail_pesanans as dp')
            ->join('produk_hampers as ph', 'dp.id_produk_hampers', '=', 'ph.id_produk_hampers')
            ->join('detail_hampers as dh', 'ph.id_produk_hampers', '=', 'dh.id_produk_hampers')
            ->join('produks as p', 'dh.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(CEIL(dp.jumlah/2) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // mainQuery part
        $mainQuery = DB::table('detail_pesanans as dp')
            ->join('produks as p', 'dp.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(IF(p.ukuran = "10x20 cm", CEIL(dp.jumlah/2), dp.jumlah) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // Union all subQuery and mainQuery
        $unionQuery = $mainQuery->unionAll($subQuery);

        // Final query to show only bahan baku where usage > stok
        $bahanBakuUsage = DB::table(DB::raw("({$unionQuery->toSql()}) as combinedQuery"))
            ->mergeBindings($unionQuery) // Bindings are necessary for subqueries
            ->join('bahan_bakus as bb', 'combinedQuery.nama', '=', 'bb.nama')
            ->select('combinedQuery.nama', DB::raw('SUM(combinedQuery.total) as total'), 'combinedQuery.satuan')
            ->groupBy('combinedQuery.nama', 'combinedQuery.satuan')
            // ->havingRaw('SUM(combinedQuery.total) > bb.stok')
            ->get();

        return response()->json([
            'message' => 'Berhasil mendapatkan penggunaan bahan baku',
            'data' => $bahanBakuUsage
        ], 200);
    }

    public function useBahanBaku($id)
    {
        // Common part of the query used in both subQuery and mainQuery
        $commonJoin = function ($join) {
            $join->on('p.id_produk', '=', 'rp.id_produk')
                ->orWhere(function ($query) {
                    $query->where('p.ukuran', '10x20 cm')
                        ->whereIn('rp.id_produk', function ($subQuery) {
                            $subQuery->select('p2.id_produk')
                                ->from('produks as p2')
                                ->where('p2.ukuran', '20x20 cm')
                                ->whereColumn('p2.nama', 'p.nama');
                        });
                });
        };

        // subQuery part
        $subQuery = DB::table('detail_pesanans as dp')
            ->join('produk_hampers as ph', 'dp.id_produk_hampers', '=', 'ph.id_produk_hampers')
            ->join('detail_hampers as dh', 'ph.id_produk_hampers', '=', 'dh.id_produk_hampers')
            ->join('produks as p', 'dh.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(CEIL(dp.jumlah/2) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // mainQuery part
        $mainQuery = DB::table('detail_pesanans as dp')
            ->join('produks as p', 'dp.id_produk', '=', 'p.id_produk')
            ->join('resep_produks as rp', $commonJoin)
            ->join('bahan_bakus as bb', 'rp.id_bahan_baku', '=', 'bb.id_bahan_baku')
            ->select('dp.id_pesanan', 'bb.nama', DB::raw('SUM(IF(p.ukuran = "10x20 cm", CEIL(dp.jumlah/2), dp.jumlah) * rp.jumlah) as total'), 'bb.satuan')
            ->where('dp.id_pesanan', $id)
            ->groupBy('dp.id_pesanan', 'bb.nama', 'bb.satuan');

        // Union all subQuery and mainQuery
        $unionQuery = $mainQuery->unionAll($subQuery);

        // Final query to get all bahan baku usage
        $bahanBakuUsage = DB::table(DB::raw("({$unionQuery->toSql()}) as combinedQuery"))
            ->mergeBindings($unionQuery) // Bindings are necessary for subqueries
            ->join('bahan_bakus as bb', 'combinedQuery.nama', '=', 'bb.nama')
            ->select('combinedQuery.nama', DB::raw('SUM(combinedQuery.total) as total'), 'bb.stok', 'combinedQuery.satuan')
            ->groupBy('combinedQuery.nama', 'combinedQuery.satuan', 'bb.stok')
            ->get();

        // Update the stok for each bahan baku
        foreach ($bahanBakuUsage as $usage) {
            DB::table('bahan_bakus')
                ->where('nama', $usage->nama)
                ->update(['stok' => DB::raw('stok - ' . $usage->total)]);
        }

        return response()->json([
            'message' => 'Berhasil mengurangi stok bahan baku',
            'data' => $bahanBakuUsage
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
