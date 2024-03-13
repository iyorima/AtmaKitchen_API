<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPesanan extends Model
{
    use HasFactory;
    protected $primaryKey = "id_status_pesanan";

    protected $fillable = [
        'id_status_pesanan',
        'id_pesanan',
        'id_karyawan',
        'status',
    ];


}
