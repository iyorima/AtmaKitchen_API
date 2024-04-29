<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Akun;
use App\Models\Pesanan;
use App\Models\DetailPesanan; 
use App\Models\Produk; 

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

    public function id_akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function history_order()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }

    public function pesananBelumSelesai() //pesanan yang perlu di bayar
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan')
            ->whereNull('total_dibayarkan')
            ->with(['detail_pesanan' => function ($query) {
                $query->with('produk');
            }]);
    }
    
    public function historiPesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan')
        ->whereHas('status_pesanan_latest', function ($query) {
            $query->where('status', 'selesai');
        })
        ->with(['detail_pesanan' => function ($query) {
            $query->with('produk');
        }]);
    }
    

    // public function detailPesanan()
    // {
    //     return $this->hasManyThrough(
    //         DetailPesanan::class,
    //         Pesanan::class,
    //         'id_pelanggan',
    //         'id_pesanan',
    //         'id_pelanggan',
    //         'id_pesanan'
    //     )->whereColumn('detail_pesanans.id_pesanan', '=', 'pesanans.id_pesanan');
    // }

    // public function produk()
    // {
    //     return $this->hasManyThrough(
    //         Produk::class,
    //         DetailPesanan::class,
    //         'id_pelanggan',
    //         'id_produk',
    //         'id_pelanggan',
    //         'id_produk'
    //     );
    // }
}
