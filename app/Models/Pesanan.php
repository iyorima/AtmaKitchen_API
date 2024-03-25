<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pesanan";

    protected $fillable = [
        'id_pesanan',
        'id_metode_pembayaran',
        'id_pelanggan',
        'tgl_order',
        'total_diskon_poin',
        'total_pesanan',
        'total_setelah_diskon',
        'total_dibayarkan',
        'total_tip',
        'verified_at',
        'accepted_at',
    ];
}
