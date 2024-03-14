<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Poin extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_poin";

    protected $fillable = [
        'id_poin',
        'id_pesanan',
        'id_pelanggan',
        'penambahan_poin',
        'total_poin',
    ];
}
