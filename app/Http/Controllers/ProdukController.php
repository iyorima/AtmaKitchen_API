<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProdukImage;
use App\Models\DetailPesanan;
// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProdukController extends Controller
{
    /**
     * Menampilkan seluruh data produk
     */
    public function index()
    {
        $produk = Produk::with('images', 'thumbnail')->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'Berhasil mendapatkan seluruh data produk',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'Produk tidak ditemukan',
            'data' => null
        ], 400);
    }

    /**
     * Membuat produk baru
     */
    public function store(Request $request)
    {
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_kategori' => 'required',
            'id_penitip',
            'nama' => 'required',
            'kapasitas' => 'required',
            'ukuran' => 'required',
            'harga_jual' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $produkData = [
            'id_kategori' => $storeData['id_kategori'],
            'nama' => $storeData['nama'],
            'kapasitas' => $storeData['kapasitas'],
            'ukuran' => $storeData['ukuran'],
            'harga_jual' => $storeData['harga_jual'],
        ];

        if (isset($storeData['id_penitip']))
            $produkData['id_penitip'] = $storeData['id_penitip'];
        else
            $produkData['id_penitip'] = null;


        $produk = Produk::create($produkData);

        $imagePaths = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $filePath = env('AZURE_STORAGE_URL') . env('AZURE_STORAGE_CONTAINER') . '/' . str_replace(' ', '%20', $image->storeAs('uploads', $fileName, 'azure'));

                ProdukImage::create([
                    'id_produk' => $produk->id_produk,
                    'image' => $filePath
                ]);
                $imagePaths[] = $filePath;
            }
        }

        $produk['images'] = $imagePaths;

        return response([
            'message' => 'Add Product Success',
            'data' => $produk
        ], 200);
    }

    /**
     * Menampilkan produk berdasarkan id produk
     */
    public function show($id)
    {
        $produk = Produk::with('images', 'thumbnail')->find($id);

        if ($produk) {
            return response([
                'message' => 'produk ' . $produk->nama . ' ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Menampilkan produk berdasarkan id kategori
     */
    public function showByKategori($id)
    {
        $produk = Produk::with('images')->where('id_kategori', $id)->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'produk ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Menampilkan produk berdasarkan id penitip
     */
    public function showByPenitip($id)
    {
        $produk = Produk::with('images')->where('id_penitip', $id)->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'produk ditemukan',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }

    /**
     * Menampilkan produk berdasarkan tanggal
     */
    public function showByDate($date)
    {
        $date = Carbon::parse($date);

        $products = Produk::with('images', 'thumbnail')->get();

        $result = [];

        foreach ($products as $product) {
            $readyStock = 0;

            if (!is_null($product->id_penitip)) {
                $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date) {
                    $query->whereDate('tgl_order', $date)->whereHas('status_pesanan', function ($query) {
                        $query->where('status', '!=', 'ditolak');
                    });;
                })
                    ->where('id_produk', $product->id_produk)
                    ->sum('jumlah');


                $remainingCapacity = $product->kapasitas - $orderCount;
                $readyStock = $remainingCapacity;
            } else {
                $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date) {
                    $query->whereDate('tgl_order', $date)->whereHas('status_pesanan', function ($query) {
                        $query->where('status', '!=', 'ditolak');
                    });;
                })
                    ->where('id_produk', $product->id_produk)
                    ->sum('jumlah');

                if (Str::contains($product->ukuran, '10x20')) {
                    $remainingCapacity = $product->kapasitas - $orderCount;
                    if ($product->kapasitas % 2 == 0) $readyStock = $remainingCapacity % 2;
                    else $readyStock = $remainingCapacity % 2 == 0 ? 1 : 0;
                }
            }

            $result[] = [
                'id_produk' => $product->id_produk,
                'nama' => $product->nama,
                'id_kategori' => $product->id_kategori,
                'id_penitip' => $product->id_penitip,
                'kapasitas' => $product->kapasitas,
                'ukuran' => $product->ukuran,
                'harga_jual' => $product->harga_jual,
                'ready_stock' => $readyStock,
                'thumbnail' => $product->thumbnail
            ];
        }

        return response([
            'message' => 'Products with ready stock for ' . $date->toDateString(),
            'data' => $result
        ], 200);
    }

    public function showReadyStockByDateAndId($date, $id)
    {
        $date = Carbon::parse($date);
        $today = Carbon::today();

        $product = Produk::with('images', 'thumbnail')->find($id);

        if (is_null($product)) {
            return response([
                'message' => 'Produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        if ($date->isSameDay($today)) {
            $readyStock = 0;

            if (!is_null($product->id_penitip)) {
                $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $id) {
                    $query->whereDate('tgl_order', $date)->whereHas('status_pesanan', function ($query) {
                        $query->where('status', '!=', 'ditolak');
                    });;
                })
                    ->where('id_produk', $id)
                    ->sum('jumlah');

                $remainingCapacity = $product->kapasitas - $orderCount;
                $readyStock = $remainingCapacity;
            } else {
                $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $id) {
                    $query->whereDate('tgl_order', $date)->whereHas('status_pesanan', function ($query) {
                        $query->where('status', '!=', 'ditolak');
                    });;
                })
                    ->where('id_produk', $id)
                    ->sum('jumlah');

                if (Str::contains($product->ukuran, '10x20')) {
                    $remainingCapacity = $product->kapasitas - $orderCount;
                    if ($product->kapasitas % 2 == 0) $readyStock = $remainingCapacity % 2;
                    else $readyStock = $remainingCapacity % 2 == 0 ? 1 : 0;
                }
            }

            $result = [
                'ready_stock' => $readyStock
            ];

            return response([
                'message' => 'Ready stock for ' . $product->nama . ' on ' . $date->toDateString(),
                'data' => $result
            ], 200);
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
                            $query->whereDate('tgl_order', $date)
                                ->whereHas('status_pesanan', function ($query) {
                                    $query->where('status', '!=', 'ditolak');
                                });
                        })
                            ->where('id_produk', $baseProduct->id_produk)
                            ->sum('jumlah');
                        $remainingCapacityBase = $baseProduct->kapasitas - $orderCountBase;
                        $baseStock = $remainingCapacityBase;

                        $stock = floor($baseStock / 2);
                    } else {
                        $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $product) {
                            $query->whereDate('tgl_order', $date)
                                ->whereHas('status_pesanan', function ($query) {
                                    $query->where('status', '!=', 'ditolak');
                                });
                        })
                            ->where('id_produk', $product->id_produk)
                            ->sum('jumlah');
                        $remainingCapacity = $product->kapasitas - $orderCount;
                        $stock = floor($remainingCapacity);
                    }
                } else {
                    $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($date, $product) {
                        $query->whereDate('tgl_order', $date)
                            ->whereHas('status_pesanan', function ($query) {
                                $query->where('status', '!=', 'ditolak');
                            });
                    })
                        ->where('id_produk', $product->id_produk)
                        ->sum('jumlah');
                    $remainingCapacity = $product->kapasitas - $orderCount;
                    $stock = floor($remainingCapacity);
                }
            }

            $result = [
                'ready_stock' => $stock
            ];

            return response([
                'message' => 'Stock for ' . $product->nama . ' on ' . $date->toDateString(),
                'data' => $result
            ], 200);
        }
    }

    public function showStockByDateAndId($id, $date)
    {
        $date = Carbon::parse($date);
        $date->addDays(2);
        $produk = Produk::with('images', 'thumbnail')->find($id);

        if (is_null($produk)) {
            return response([
                'message' => 'Produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        $stok = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDate = $date->copy()->addDays($i);

            if (!is_null($produk->id_penitip)) {
                $stock = 0;
            } else {
                $ukuran = $produk->ukuran;

                if ($ukuran === '20x20 cm') {
                    $baseProduct = Produk::where('nama', $produk->nama)
                        ->where('ukuran', '10x20 cm')
                        ->first();

                    if ($baseProduct) {
                        $orderCountBase = DetailPesanan::whereHas('pesanan', function ($query) use ($currentDate) {
                            $query->whereDate('tgl_order', $currentDate)
                                ->whereHas('status_pesanan_latest', function ($query) {
                                    $query->where('status', '!=', 'Dibatalkan otomatis');
                                });
                        })
                            ->where('id_produk', $baseProduct->id_produk)
                            ->sum('jumlah');
                        $remainingCapacityBase = $baseProduct->kapasitas - $orderCountBase;
                        $baseStock = $remainingCapacityBase;
                        $stock = floor($baseStock / 2);
                    } else {
                        $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($currentDate, $id) {
                            $query->whereDate('tgl_order', $currentDate)
                                ->whereHas('status_pesanan_latest', function ($query) {
                                    $query->where('status', '!=', 'Dibatalkan otomatis');
                                });
                        })
                            ->where('id_produk', $id)
                            ->sum('jumlah');
                        $stock = $produk->kapasitas - $orderCount;
                    }
                } else {
                    $orderCount = DetailPesanan::whereHas('pesanan', function ($query) use ($currentDate, $id) {
                        $query->whereDate('tgl_order', $currentDate)
                            ->whereHas('status_pesanan_latest', function ($query) {
                                $query->where('status', '!=', 'Dibatalkan otomatis');
                            });
                    })
                        ->where('id_produk', $id)
                        ->sum('jumlah');
                    $stock = $produk->kapasitas - $orderCount;
                }
            }

            $stok[] = [
                'date' => $currentDate->toDateString(),
                'stock' => $stock,
            ];
        }

        return response([
            'message' => 'Ready stock for the next 7 days',
            'data' => $stok
        ], 200);
    }


    /**
     * Mengubah data produk
     * TODO: cari cara supaya foto bisa dinamis
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::with('images')->find($id);

        if (is_null($produk)) {
            return response([
                'message' => 'produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_kategori' => 'required',
            'id_penitip',
            'nama' => 'required',
            'kapasitas' => 'required',
            'ukuran' => 'required',
            'harga_jual' => 'required',
            // 'image.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        // foreach ($produk->images as $image) {
        //     $fileName = basename($image->image);
        //     $azurePath = 'uploads/' . $fileName;
        //     Storage::disk('azure')->delete($azurePath);
        //     $image->delete();
        // }

        $produkData = [
            'id_kategori' => $updateData['id_kategori'],
            'nama' => $updateData['nama'],
            'kapasitas' => $updateData['kapasitas'],
            'ukuran' => $updateData['ukuran'],
            'harga_jual' => $updateData['harga_jual'],
            'id_penitip' => $updateData['id_penitip'] ? $updateData['id_penitip'] : null,
        ];

        $produk->update($produkData);

        // $imagePaths = [];
        // if ($request->hasFile('image')) {
        //     foreach ($request->file('image') as $image) {
        //         $fileName = time() . '_' . $image->getClientOriginalName();
        //         // save file to azure blob virtual directory uplaods in your container
        //         $filePath = env('AZURE_STORAGE_URL') . env('AZURE_STORAGE_CONTAINER') . '/' . str_replace(' ', '%20', $image->storeAs('uploads', $fileName, 'azure'));

        //         ProdukImage::create([
        //             'id_produk' => $produk->id_produk,
        //             'image' => $filePath
        //         ]);
        //         $imagePaths[] = $filePath;
        //     }
        // }

        $updatedProduk = Produk::with('images')->find($id);

        if ($produk->save()) {
            return response([
                'message' => 'produk berhasil diubah',
                'data' => [
                    'produk' => $updatedProduk,
                    // 'image' => $imagePaths
                ]
            ], 200);
        }

        return response([
            'message' => 'ubah produk gagal',
            'data' => null
        ], 400);
    }

    /**
     * Menghapus data produk berdasarkan id produk
     */
    public function destroy($id)
    {
        $produk = Produk::with('images')->find($id);

        if (is_null($produk)) {
            return response([
                'message' => 'produk tidak ditemukan',
                'data' => null
            ], 404);
        }

        foreach ($produk->images as $image) {
            Storage::disk('azure')->delete($image->image);
            $image->delete();
        }

        if ($produk->delete()) {
            return response([
                'message' => 'produk ' . $produk->nama . ' berhasil dihapus',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk gagal dihapus',
            'data' => null
        ], 400);
    }

    /**
     * Mencari data produk berdasarkan nama
     */
    public function search(Request $request)
    {
        $keyword = $request->all();

        $validate = Validator::make($keyword, [
            'keyword' => 'string|max:255',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }

        $produk = Produk::where('name', 'ilike', '%' . $keyword['keyword'] . '%')->get();

        if ($produk->isNotEmpty()) {
            return response([
                'message' => 'pencarian berhasil',
                'data' => $produk
            ], 200);
        }

        return response([
            'message' => 'produk tidak ditemukan',
            'data' => null
        ], 404);
    }
}
