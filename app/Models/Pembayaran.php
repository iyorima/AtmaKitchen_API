<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

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
