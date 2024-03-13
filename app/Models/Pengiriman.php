<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $primaryKey = "id_pengiriman";

    protected $fillable = [
        'id_pengiriman',
        'id_pesanan',
        'id_kategori_pengiriman',
        'id_kurir',
        'jarak',
        'harga',
    ];


}
