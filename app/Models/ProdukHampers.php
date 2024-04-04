<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukHampers extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_produk_hampers";

    protected $fillable = [
        'id_produk_hampers',
        'nama',
        'harga_jual',
        'image'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function detailHampers()
    {
        return $this->hasMany(DetailHampers::class, 'id_produk_hampers');
    }
}
