<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_produk_image";

    protected $fillable = [
        'id_produk_image',
        'id_produk',
        'image',
    ];
}
