<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_produk";

    protected $fillable = [
        'id_produk',
        'id_kategori',
        'id_penitip',
        'nama',
        'kapasitas',
        'ukuran',
        'harga_jual'
    ];
}
