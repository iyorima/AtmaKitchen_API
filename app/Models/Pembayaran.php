<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $primaryKey = "id_pembayaran";

    protected $fillable = [
        'id_pembayaran',
        'id_pesanan',
        'id_metode_pembayaran',
        'total dibayarkan',
        'total_tip',
        'verified_at'
    ];


}
