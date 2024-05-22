<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\PresensiAbsen;


//BELUM TAU BENER GAK DR PRESENSI MOBILE GIMANA?

class LaporanPresensiGajiController extends Controller
{
    public function generateLaporan(Request $request, $tahun = null, $bulan = null) // buat untuk 2
    {

        $bulan = $bulan ?? now()->month; //kalo ada bulan, klo gada ke sekarang
        $tahun = $tahun ?? now()->year;

        $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun); //jumlah hari dalam bulan tertentu

        $tanggalCetak = now()->format('Y-m-d');

        $infoLaporan = [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tanggal_cetak' => $tanggalCetak,
        ];

        $karyawan = Karyawan::all();

        $laporan = [];

        foreach ($karyawan as $pegawai) {
            $presensi = PresensiAbsen::where('id_karyawan', $pegawai->id_karyawan)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->count();

            $jumlahBolos = $presensi;

            $jumlahHadir = $jumlahHari - $jumlahBolos;

            $totalGajiHarian = $pegawai->gaji_harian * $jumlahHadir;

            $bonusRajin = $pegawai->bonus ?? 0;

            $totalGaji = $totalGajiHarian + $bonusRajin;

            $laporan[] = [
                'nama' => $pegawai->nama,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_bolos' => $jumlahBolos,
                'honor_harian' => $pegawai->gaji_harian,
                'bonus_rajin' => $bonusRajin,
                'total' => $totalGaji,
            ];
        }

        $totalKeseluruhanGaji = array_reduce($laporan, function ($carry, $item) {
            return $carry + $item['total'];
        }, 0);

        $laporan['total_keseluruhan_gaji'] = $totalKeseluruhanGaji;

        $laporan = array_merge($infoLaporan, ['laporan' => $laporan]);

        return response()->json($laporan);
    }
}
