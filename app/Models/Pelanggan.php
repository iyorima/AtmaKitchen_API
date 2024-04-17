<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Akun;
use App\Models\Pesanan;
use App\Models\DetailPesanan; // Tambahkan ini
use App\Models\Produk; // Tambahkan ini

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pelanggan";

    protected $fillable = [
        'id_pelanggan',
        'id_akun',
        'nama',
        'tgl_lahir',
        'telepon'
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }

    public function pesananBelumSelesai()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan')
        ->whereNull('total_dibayarkan')
        ->with('detailPesanan.produk', 'detailPesanan.pesanan');
    }

    public function historiPesanan()
    {
        return $this->hasManyThrough(
            StatusPesanan::class,
            Pesanan::class,
            'id_pelanggan',
            'id_pesanan',
            'id_pelanggan',
            'id_pesanan'
        )->where('status', 'selesai')->with(['detailPesanans' => function ($query) {
            $query->whereColumn('detail_pesanans.id_pesanan', '=', 'pesanans.id_pesanan');
        }, 'detailPesanans.produk']);
    }
    
    
    public function detailPesanan() 
    {
        return $this->hasManyThrough(
            DetailPesanan::class,
            Pesanan::class,
            'id_pelanggan',
            'id_pesanan',
            'id_pelanggan',
            'id_pesanan'
        )->whereColumn('detail_pesanans.id_pesanan', '=', 'pesanans.id_pesanan'); 
    }

    public function produk() // Tambahkan relasi produk
    {
        return $this->hasManyThrough(
            Produk::class,
            DetailPesanan::class,
            'id_pelanggan',
            'id_produk',
            'id_pelanggan',
            'id_produk'
        );
    }
}
