<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pelanggan;
use App\Models\DetailPesanan;
use App\Models\Pengiriman;
use App\Models\Produk;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pesanan";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_metode_pembayaran',
        'id_pelanggan',
        'tgl_order',
        'total_diskon_poin',
        'total_pesanan',
        'total_setelah_diskon',
        'total_dibayarkan',
        'total_tip',
        'verified_at',
        'accepted_at',
    ];

    public function detail_pesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function produk()
    {
        return $this->hasManyThrough(Produk::class, DetailPesanan::class, 'id_pesanan', 'id_produk');
    }
}
