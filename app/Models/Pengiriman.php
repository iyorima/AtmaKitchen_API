<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengiriman extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pengiriman";

    protected $fillable = [
        'id_pengiriman',
        'id_pesanan',
        'id_kategori_pengiriman',
        'id_kurir',
        'jarak',
        'harga',
        'nama',
        'telepon',
        'alamat'
    ];
}
