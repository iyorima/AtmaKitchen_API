<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_karyawan";

    protected $fillable = [
        'id_karyawan',
        'id_akun',
        'nama',
        'gaji_harian',
        'bonus',
        'telepon',
        'alamat',
    ];
}