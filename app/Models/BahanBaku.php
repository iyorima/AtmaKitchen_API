<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BahanBaku extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_bahan_baku";

    protected $fillable = [
        'id_bahan_baku',
        'nama',
        'satuan',
        'stok',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
<<<<<<< HEAD
=======
        // 'updated_at',
>>>>>>> c9bcd67d3210963338c2a33e8fa3dbf1aed1dcaa
        'deleted_at',
    ];
}
