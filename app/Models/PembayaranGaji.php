<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranGaji extends Model
{
    use HasFactory;

    protected $primaryKey = "id_pembayaran_gaji";

    protected $fillable = [
        'id_pembayaran_gaji',
        'id_karyawan',
        'total',
        'bonus',
    ];

}
