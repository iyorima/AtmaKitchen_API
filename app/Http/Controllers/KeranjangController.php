<?php

namespace App\Http\Controllers;

use App\Models\DetailKeranjang;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\ProdukHampers;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keranjangs = Keranjang::with('detail_keranjang')->get();

        if ($keranjangs->isEmpty()) {
            return response([
                'message' => 'Keranjang tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response([
            'message' => 'Berhasil mendapatkan data keranjang',
            'data' => $keranjangs,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a new item in the shopping cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        // 📃 Validator
        $validate = Validator::make(
            $storeData,
            [
                'id_pelanggan' => 'required',
                'jumlah' => 'required|min:1',
            ]
        );

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $keranjang = Keranjang::where('id_pelanggan', $request->id_pelanggan)->first();

        if (!is_null($keranjang)) {
            $data = [
                'id_keranjang' => $keranjang->id_keranjang,
                'jumlah' => $request->jumlah,
            ];

            if (isset($storeData['id_produk'])) {
                $data['id_produk'] = $storeData['id_produk'];
            } else {
                $data['id_produk_hampers'] = $storeData['id_produk_hampers'];
            }
        }

        if (is_null($keranjang)) {
            $keranjang = Keranjang::create([
                'id_pelanggan' => $request->id_pelanggan,
            ]);

            $data = [
                'id_keranjang' => $keranjang->id_keranjang,
                'jumlah' => $request->jumlah,
            ];

            if (isset($storeData['id_produk'])) {
                $data['id_produk'] = $storeData['id_produk'];
            } else {
                $data['id_produk_hampers'] = $storeData['id_produk_hampers'];
            }
        }

        $detail_keranjang = DetailKeranjang::create($data);

        return response([
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'data' => $detail_keranjang,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id, $date)
    {
        $keranjang = Keranjang::with('detail_keranjang.produk.thumbnail', 'detail_keranjang.hampers')->where('id_pelanggan', $id)->first();

        if (is_null($keranjang)) {
            return response([
                'message' => 'Keranjang tidak tersedia',
                'data' => null,
            ], 400);
        }

        $date = Carbon::parse($date);
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        foreach ($keranjang->detail_keranjang as $detail) {
            if (!is_null($detail->id_produk)) {
                $product = Produk::with('images', 'thumbnail')->find($detail->id_produk);
                if ($date->isSameDay($today) || $date->isSameDay($tomorrow)) {
                    $readyStock = 0;

                    if (!is_null($product->id_penitip)) {
                        $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $id) {
                            $query->whereDate('tgl_order', $date);
                        })
                            ->where('id_produk', $detail->id_produk)
                            ->sum('jumlah');

                        $remainingCapacity = $product->kapasitas - $orderCount;
                        $readyStock = $remainingCapacity;
                    } else {
                        $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $id) {
                            $query->whereDate('tgl_order', $date);
                        })
                            ->where('id_produk', $detail->id_produk)
                            ->sum('jumlah');

                        if (Str::contains($product->ukuran, '10x20')) {
                            $remainingCapacity = $product->kapasitas - $orderCount;
                            if ($product->kapasitas % 2 == 0) $readyStock = $remainingCapacity % 2;
                            else $readyStock = $remainingCapacity % 2 == 0 ? 1 : 0;
                        }
                    }
                    $detail->ready_stock = $readyStock;
                } else {
                    $stock = 0;

                    if (!is_null($product->id_penitip)) {
                        $stock = 0;
                    } else {
                        $ukuran = $product->ukuran;

                        if ($ukuran === '20x20 cm') {
                            $baseProduct = Produk::where('nama', $product->nama)
                                ->where('ukuran', '10x20 cm')
                                ->first();

                            if ($baseProduct) {
                                $orderCountBase = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $baseProduct) {
                                    $query->whereDate('tgl_order', $date);
                                })
                                    ->where('id_produk', $baseProduct->id_produk)
                                    ->sum('jumlah');
                                $remainingCapacityBase = $baseProduct->kapasitas - $orderCountBase;
                                $baseStock = $remainingCapacityBase;

                                $stock = floor($baseStock / 2);
                            } else {
                                $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $product) {
                                    $query->whereDate('tgl_order', $date);
                                })
                                    ->where('id_produk', $product->id_produk)
                                    ->sum('jumlah');
                                $remainingCapacity = $product->kapasitas - $orderCount;
                                $stock = $remainingCapacity;
                            }
                        } else {
                            // Calculate stock normally for other sizes
                            $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $product) {
                                $query->whereDate('tgl_order', $date);
                            })
                                ->where('id_produk', $product->id_produk)
                                ->sum('jumlah');
                            $remainingCapacity = $product->kapasitas - $orderCount;
                            $stock = $remainingCapacity;
                        }
                    }
                    $detail->ready_stock = $stock;
                }
            } else {
                $hampers = ProdukHampers::with(['detailHampers.produk'])->find($detail->id_produk_hampers);
                if ($hampers && $date->isSameDay($today) || $date->isSameDay($tomorrow)) {
                    $minStock = 0;
                } else {
                    $minStock = null;

                    foreach ($hampers->detailHampers as $detailHampers) {
                        $product = $detailHampers->produk;

                        $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date) {
                            $query->whereDate('tgl_order', $date);
                        })
                            ->where('id_produk', $detail->id_produk_hampers)
                            ->sum('jumlah');

                        $productStock = $product->kapasitas - $orderCount;

                        if (is_null($minStock) || $productStock < $minStock) {
                            $minStock = $productStock;
                        }
                    }
                }
                $detail->ready_stock = $minStock;
            }
        }

        return response([
            'message' => 'Keranjang ditemukan',
            'data' => $keranjang,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $keranjang = Keranjang::with('detail_keranjang')->find($id);

        if (is_null($keranjang)) {
            return response([
                'message' => 'Keranjang tidak temukan',
                'data' => null,
            ], 400);
        }

        $keranjang->detail_keranjang->each->delete();

        $keranjang->delete();

        return response([
            'message' => 'Keranjang berhasil dihapus',
            'data' => $keranjang,
        ], 200);
    }
}
