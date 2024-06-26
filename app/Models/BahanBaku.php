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
        'stok_minumum',
        'updated_at',
    ];

    protected $hidden = [
        'created_at',
        'deleted_at',
    ];

    protected $casts = [
        'id_bahan_baku' => 'string'
    ];
}
