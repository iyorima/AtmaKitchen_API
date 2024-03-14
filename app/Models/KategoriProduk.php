<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_kategori";

    protected $fillable = [
        'id_kategori',
        'kategori',
    ];
}
