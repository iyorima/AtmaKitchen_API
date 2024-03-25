<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailHampers extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_produk_hampers',
        'id_produk',
    ];
}
