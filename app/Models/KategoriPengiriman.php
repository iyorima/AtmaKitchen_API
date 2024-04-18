<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriPengiriman extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_kategori_pengiriman";

    protected $fillable = [
        'id_kategori_pengiriman',
        'jarak_minimum',
        'harga',
    ];

    public function kategoriPengiriman()
    {
        return $this->belongsTo(KategoriPengiriman::class, 'id_kategori_pengiriman');
    }
}
