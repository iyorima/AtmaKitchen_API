<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengeluaranLainnya extends Model
{
    use HasFactory;

    protected $primaryKey = "id_pengeluaran_lainnya";

    protected $fillable = [
        'id_pengeluaran_lainnya',
        'id_karyawan',
        'nama',
        'biaya',
    ];
}
