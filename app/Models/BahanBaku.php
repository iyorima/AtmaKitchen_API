<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;
    protected $primaryKey = "id_bahan_baku";

    protected $fillable = [
        'id_bahan_baku',
        'nama',
        'satuan',
        'stok',
    ];


}
