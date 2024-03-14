<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailPesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_detail_pesanan";

    protected $fillable = [
        'id_detail_pesanan',
        'id_pesanan',
        'id_produk',
        'kategori',
        'nama_produk',
        'harga',
        'jumlah',
    ];
}
