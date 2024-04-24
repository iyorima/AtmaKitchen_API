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

    protected $hidden = [
        'created_at',
        'deleted_at'
    ];

    public function detail_keranjang()
    {
        return $this->hasMany(DetailKeranjang::class, 'id_keranjang');
    }
}
