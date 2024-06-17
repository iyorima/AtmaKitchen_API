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

    public function akun()
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

    //yang status selesai
    public function historiPesanan()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan')
            // ->whereHas('status_pesanan_latest', function ($query) {
            //     $query->where('status', 'selesai');
            // })

            ->with(['detail_pesanan.produk.images', 'status_pesanan_latest', 'pengiriman', 'poins']);
    }

    public function poins()
    {
        return $this->hasOne(Poin::class, 'id_pelanggan')->latestOfMany('id_poin');
    }

    public function saldo()
    {
        return $this->hasOne(SaldoPelanggan::class, 'id_akun')->latestOfMany('id_saldo_pelanggan');
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class, 'id_pelanggan');
    }

    public function count_keranjang()
    {
        return $this->hasOne(Keranjang::class, 'id_pelanggan');
    }

    // public function count_keranjang()
    // {
    //     return $this->keranjang()->count();
    // }
}
