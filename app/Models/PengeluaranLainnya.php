<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranLainnya extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pengeluaran_lainnya";

    protected $fillable = [
        'id_pengeluaran_lainnya',
        'id_karyawan',
        'nama',
        'biaya',
    ];
}
