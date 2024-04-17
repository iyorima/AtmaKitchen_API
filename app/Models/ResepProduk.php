<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResepProduk extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_resep_produk";

    protected $fillable = [
        'id_resep_produk',
        'id_produk',
        'id_bahan_baku',
        'satuan',
        'jumlah',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function id_bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }
}
