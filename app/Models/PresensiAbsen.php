<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresensiAbsen extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_presensi";

    protected $fillable = [
        'id_presensi',
        'id_karyawan',
        'tanggal',
    ];

    public function id_karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan');
    }
}
