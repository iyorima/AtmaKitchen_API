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

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function images()
    {
        return $this->hasMany(ProdukImage::class, 'id_produk');
    }

    public function resep()
    {
        return $this->hasMany(ResepProduk::class, 'id_produk');
    }
    public function bahan_baku()
    {
        return $this->hasMany(ResepProduk::class, 'id_produk');
    }

    public function penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }

    public function thumbnail()
    {
        return $this->hasOne(ProdukImage::class, 'id_produk');
    }
}
