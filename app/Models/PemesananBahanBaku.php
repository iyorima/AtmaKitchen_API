<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemesananBahanBaku extends Model
{
    use HasFactory;

    protected $primaryKey = "id_pemesanan_bahan_baku";

    protected $fillable = [
        'id_pemesanan_bahan_baku',
        'id_bahan_baku',
        'nama',
        'satuan',
        'jumlah',
        'harga_beli',
        'total',
    ];


}
