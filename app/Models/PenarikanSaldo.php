<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanSaldo extends Model
{
    use HasFactory;

    protected $primaryKey = "id_penarikan_saldo";

    protected $fillable = [
        'id_penarikan_saldo',
        'id_akun',
        'jumlah_penarikan',
        'transfer_at',
    ];

}
