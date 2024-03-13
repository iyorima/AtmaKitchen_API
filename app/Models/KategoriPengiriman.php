<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengiriman extends Model
{
    use HasFactory;

    protected $primaryKey = "id_kategori_pengiriman";

    protected $fillable = [
        'id_kategori_pengiriman',
        'jarak_minimum',
        'harga',
    ];


}
