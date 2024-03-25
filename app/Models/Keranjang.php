<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Keranjang extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_keranjang";

    protected $fillable = [
        'id_keranjang',
        'id_pelanggan',
    ];
}
