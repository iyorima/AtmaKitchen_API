<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Produk;
use App\Models\Pesanan;

class DetailPesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_detail_pesanan";

    protected $fillable = [
        'id_detail_pesanan',
        'id_pesanan',
        'id_produk',
        'id_produk_hampers',
        'kategori',
        'nama_produk',
        'harga',
        'jumlah',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan');
    }
}
