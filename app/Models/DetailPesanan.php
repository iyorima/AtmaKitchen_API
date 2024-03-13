<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    use HasFactory;

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
