<?php

namespace App\Http\Controllers;

use App\Models\Poin;
use App\Models\Pelanggan;
use App\Http\Requests\StorePoinRequest;
use App\Http\Requests\UpdatePoinRequest;

class PoinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promo = Poin::all();

        return response([
            "message" => "Berhasil mendapatkan poin",
            "data" => $promo
        ]);
    }

    public function showByPelanggan($id)
    {
        $poin = Poin::where('id_pelanggan', $id)->orderBy('id_poin', 'desc')
            ->latest()
            ->value('total_poin');

        if ($poin) {
            return response([
                'message' => 'Berhasil mendapatkan poin',
                'data' => $poin
            ]);
        } else {
            return response([
                'message' => 'Poin tidak ditemukan untuk pelanggan yang diberikan',
                'data' => null
            ], 404);
        }
    }

    public function showGetPoin($total_harga)
    {
        $poin = 0;
        if ($total_harga >= 1000000) {
            $poin += (int)($total_harga / 1000000) * 200;
            $total_harga %= 1000000;
        }
        if ($total_harga >= 500000) {
            $poin += (int)($total_harga / 500000) * 75;
            $total_harga %= 500000;
        }
        if ($total_harga >= 100000) {
            $poin += (int)($total_harga / 100000) * 15;
            $total_harga %= 100000;
        }
        if ($total_harga >= 10000) $poin += (int)($total_harga / 10000) * 1;

        // $pelanggan = Pelanggan::find($id);
        // if ($pelanggan) {
        //     $tgl_order = new \DateTime($tgl_order);
        //     $tgl_lahir = new \DateTime($pelanggan->tgl_lahir);
        //     $tgl_ultah_tahun_ini = new \DateTime($tgl_lahir->format('Y') . '-' . $tgl_lahir->format('m') . '-' . $tgl_lahir->format('d'));

        //     $batas_bawah_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('-3 days');
        //     $batas_atas_tgl_lahir = (clone $tgl_ultah_tahun_ini)->modify('+3 days');

        //     if ($tgl_order >= $batas_bawah_tgl_lahir && $tgl_order <= $batas_atas_tgl_lahir) {
        //         $poin *= 2;
        //     }
        // }

        return response([
            'message' => 'Berhasil mendapatkan poin',
            'data' => $poin
        ]);
    }

    public function showByPesanan($id_pelanggan, $id_pesanan)
    {
        $poin = Poin::where('id_pelanggan', $id_pelanggan)->where('id_pesanan', $id_pesanan)->orderBy('id_poin', 'desc')
            ->latest()
            ->value('total_poin');

        if ($poin) {
            return response([
                'message' => 'Berhasil mendapatkan poin',
                'data' => $poin
            ]);
        } else {
            return response([
                'message' => 'Poin tidak ditemukan untuk pelanggan yang diberikan',
                'data' => null
            ], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePoinRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Poin $poin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Poin $poin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePoinRequest $request, Poin $poin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Poin $poin)
    {
        //
    }
}
