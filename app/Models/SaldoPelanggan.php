<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoPelanggan extends Model
{
    use HasFactory;

    protected $primaryKey = "id_saldo_pelanggan";

    protected $fillable = [
        'id_saldo_pelanggan',
        'id_akun',
        'id_pesanan',
        'saldo',
        'total_saldo',
    ];


}
