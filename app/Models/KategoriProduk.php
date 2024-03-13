<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $primaryKey = "id_kategori";

    protected $fillable = [
        'id_kategori',
        'kategori',
    ];


}
