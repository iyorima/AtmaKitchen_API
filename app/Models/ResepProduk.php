<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepProduk extends Model
{
    use HasFactory;
    protected $primaryKey = "id_resep_produk";

    protected $fillable = [
        'id_resep_produk',
        'id_produk',
        'id_bahan_baku',
        'satuan',
        'jumlah',
    ];


}
