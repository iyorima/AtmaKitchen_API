<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_status_pesanan";

    protected $fillable = [
        'id_status_pesanan',
        'id_pesanan',
        'id_karyawan',
        'status',
    ];
}
