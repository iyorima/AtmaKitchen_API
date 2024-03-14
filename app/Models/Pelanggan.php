<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pelanggan";

    protected $fillable = [
        'id_pelanggan',
        'id_akun',
        'nama',
        'tgl_lahir',
    ];
}
