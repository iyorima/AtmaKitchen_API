<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    use HasFactory;

    protected $primaryKey = "id_metode_pembayaran";

    protected $fillable = [
        'id_metode_pembayaran',
        'nama',
    ];


}
