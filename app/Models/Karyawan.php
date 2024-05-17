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
        'presensi_only_one'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function presensi()
    {
        return $this->hasMany(PresensiAbsen::class, 'id_karyawan');
    }

    public function presensiOnlyOne()
    {
        return $this->hasOne(PresensiAbsen::class, 'id_karyawan');
    }
}
