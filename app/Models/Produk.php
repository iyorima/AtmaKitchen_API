<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $primaryKey = "id_produk";

    protected $fillable = [
        'id_produk',
        'id_kategori',
        'id_penitip',
        'nama',
        'kapasitas',
        'harga_jual'
        
    ];


}
