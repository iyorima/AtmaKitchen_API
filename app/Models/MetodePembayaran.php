<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetodePembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_metode_pembayaran";

    protected $fillable = [
        'id_metode_pembayaran',
        'nama',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
