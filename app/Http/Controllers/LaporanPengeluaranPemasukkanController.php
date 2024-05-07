<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\PengeluaranLainnya;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPengeluaranPemasukkanController extends Controller
{
    public function laporanPengeluaranPemasukkan(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun', Carbon::now()->format('Y-m'));

        [$tahun, $bulan] = explode('-', $bulanTahun);
        $namaBulan = Carbon::createFromDate(null, $bulan, null)->monthName;

        $tanggalAwalBulan = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhirBulan = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $pemasukkan = $this->getPemasukkanBulanan($tanggalAwalBulan, $tanggalAkhirBulan);
        $pengeluaran = $this->getPengeluaranBulanan($tanggalAwalBulan, $tanggalAkhirBulan);

        $totalPemasukkan = $pemasukkan->sum('total');
        $totalPengeluaran = $pengeluaran->sum('biaya');

        $output = [
            'tanggal_cetak' => now()->format('Y-m-d'),
            'bulan_tahun' => [
                'bulan' => $namaBulan,
                'tahun' => $tahun
            ],
            'pemasukkan' => $pemasukkan->toArray(),
            'pengeluaran' => $pengeluaran->toArray(),
            'total_pemasukkan' => $totalPemasukkan,
            'total_pengeluaran' => $totalPengeluaran
        ];

        return response()->json($output);
    }

    private function getPemasukkanBulanan($tanggalAwalBulan, $tanggalAkhirBulan) //masih error
    {
        return Pesanan::selectRaw('SUM(total_setelah_diskon) as total')
            ->whereBetween('tgl_order', [$tanggalAwalBulan, $tanggalAkhirBulan])
            ->groupBy(DB::raw('DATE_FORMAT(tgl_order, "%Y-%m")'))
            ->get();
    }

    private function getPengeluaranBulanan($tanggalAwalBulan, $tanggalAkhirBulan) //masih error
    {
        return PengeluaranLainnya::select('nama', DB::raw('SUM(biaya) as biaya'))
            ->whereBetween('tanggal', [$tanggalAwalBulan, $tanggalAkhirBulan])
            ->groupBy('kategori')
            ->get();
    }
}
