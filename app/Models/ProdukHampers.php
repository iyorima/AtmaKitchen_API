<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukHampers extends Model
{
    use HasFactory;

    protected $primaryKey = "id_produk_hampers";

    protected $fillable = [
        'id_produk_hampers',
        'id_produk',
        'nama',
        'satuan',
        'jumlah',
    ];


}
