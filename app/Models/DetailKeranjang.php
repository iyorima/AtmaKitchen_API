<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailKeranjang extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_detail_keranjang";

    protected $fillable = [
        'id_detail_keranjang',
        'id_keranjang',
        'id_produk',
        'id_produk_hampers',
        'jumlah'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function hampers()
    {
        return $this->belongsTo(ProdukHampers::class, 'id_produk_hampers');
    }
}
