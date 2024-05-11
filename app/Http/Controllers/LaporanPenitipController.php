<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penitip;
use App\Models\Pesanan;
use App\Models\Produk;

class LaporanPenitipController extends Controller
{
    public function rekapTransaksiPenitip(Request $request)
    {
        //bulan dan tahun generate saat ini
        $bulanTahun = $request->input('bulan_tahun', now()->format('Y-m'));
        [$tahun, $bulan] = explode('-', $bulanTahun);

        $rekapTransaksi = $this->generateRekapTransaksi($tahun, $bulan);

        return response()->json($rekapTransaksi);
    }

    public function rekapTransaksiPenitipByDate($tahun, $bulan) //set bulan dan tahun sesuai route
    {
        $rekapTransaksi = $this->generateRekapTransaksi($tahun, $bulan);

        return response()->json($rekapTransaksi);
    }

    private function generateRekapTransaksi($tahun, $bulan)
    {
        $penitips = Penitip::all();
    
        $rekapTransaksi = [];
    
        foreach ($penitips as $penitip) {
            
            $pesananPenitip = Pesanan::whereHas('detail_pesanan.produk', function ($query) use ($penitip) {
                $query->where('id_penitip', $penitip->id_penitip);
            })->whereYear('tgl_order', $tahun)
                ->whereMonth('tgl_order', $bulan)
                ->with('detail_pesanan.produk')
                ->get();
    
            $transaksiPenitip = [];
    
           //init
            $produkTransaksi = [];
    
           
            foreach ($pesananPenitip as $pesanan) {
                foreach ($pesanan->detail_pesanan as $detailPesanan) {
                    $produkId = $detailPesanan->produk->id_produk;
    
                    if (!isset($produkTransaksi[$produkId])) {
                        $produkTransaksi[$produkId] = [
                            'jumlah' => 0,
                            'total' => 0,
                            'komisi' => 0,
                            'yang_diterima' => 0
                        ];
                    }
    
                    $produkTransaksi[$produkId]['jumlah'] += $detailPesanan->jumlah;
                    $produkTransaksi[$produkId]['total'] += $detailPesanan->jumlah * $detailPesanan->produk->harga_jual;
                    $produkTransaksi[$produkId]['komisi'] += $detailPesanan->jumlah * $detailPesanan->produk->harga_jual * 0.2;
                    $produkTransaksi[$produkId]['yang_diterima'] += $detailPesanan->jumlah * $detailPesanan->produk->harga_jual * 0.8;
                }
            }
    
          
            foreach ($produkTransaksi as $produkId => $transaksiProduk) {
                $produk = Produk::find($produkId);
    
               
                $transaksiPenitip[] = [
                    'nama_produk' => $produk->nama,
                    'qty' => $transaksiProduk['jumlah'],
                    'harga_jual' => $produk->harga_jual,
                    'total' => $transaksiProduk['total'],
                    'komisi' => $transaksiProduk['komisi'],
                    'yang_diterima' => $transaksiProduk['yang_diterima']
                ];
            }
    
            $rekapTransaksi[] = [
                'id_penitip' => $penitip->id_penitip,
                'nama_penitip' => $penitip->nama,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tanggal_cetak' => now()->format('Y-m-d'),
                'transaksi' => $transaksiPenitip,
            ];
        }
    
        return $rekapTransaksi;
    }
    
}
