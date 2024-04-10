<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresensiAbsen extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_karyawan";

    protected $fillable = [
        'id_presensi',
        'id_karyawan',
    ];

    public function id_karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
