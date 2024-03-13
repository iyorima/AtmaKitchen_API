<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    use HasFactory;

    protected $primaryKey = "id_poin";

    protected $fillable = [
        'id_poin',
        'id_pesanan',
        'id_pelanggan',
        'penambahan_poin',
        'total_poin',
    ];


}
