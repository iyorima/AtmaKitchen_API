<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PengeluaranLainnya;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporanPengeluaranPemasukkanController extends Controller
{
    public function laporanPengeluaranPemasukkan(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun', Carbon::now()->format('Y-m'));

        [$tahun, $bulan] = explode('-', $bulanTahun);
        $namaBulan = Carbon::createFromDate(null, $bulan, null)->monthName;

        $pemasukkanPesanan = Pesanan::whereYear('tgl_order', $tahun)
        ->whereMonth('tgl_order', $bulan)
        ->whereNotNull('total_dibayarkan') 
        ->sum('total_setelah_diskon');


        $tipPesanan = Pesanan::whereYear('tgl_order', $tahun)
            ->whereMonth('tgl_order', $bulan)
            ->sum('total_tip');

        $pengeluaranLainnya = PengeluaranLainnya::select('nama', DB::raw('SUM(biaya) as biaya'))
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->groupBy('nama')
            ->get();

        $pengeluaranFormatted = [];
        foreach ($pengeluaranLainnya as $item) {
            $pengeluaranFormatted[$item->nama] = $item->biaya;
        }

        $totalPemasukkan = $pemasukkanPesanan + $tipPesanan;
        $totalPengeluaran = array_sum($pengeluaranFormatted);

        $output = [
            'tanggal_cetak' => now()->format('Y-m-d'),
            'bulan_tahun' => [
                'bulan' => $namaBulan,
                'tahun' => $tahun
            ],
            'pemasukkan' => [
                'penjualan' => $pemasukkanPesanan,
                'tip' => $tipPesanan,
            ],
            'pengeluaran' => $pengeluaranFormatted,
            'total_pemasukkan' => $totalPemasukkan,
            'total_pengeluaran' => $totalPengeluaran
        ];

        return response()->json($output);
    }

    public function getPengeluaranPemasukkan($tahun, $bulan)
    {
        $request = new Request();
        $request->merge([
            'bulan_tahun' => "$tahun-$bulan"
        ]);

        $laporan = $this->laporanPengeluaranPemasukkan($request);

        return response()->json($laporan, 200);
    }


}