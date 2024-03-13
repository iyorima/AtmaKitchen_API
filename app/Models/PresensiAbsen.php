<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiAbsen extends Model
{
    use HasFactory;

    protected $primaryKey = "id_karyawan";

    protected $fillable = [
        'id_presensi',
        'id_karyawan',
    ];


}
