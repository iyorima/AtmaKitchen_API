<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotifikasiRead extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_notifikasi_read";

    protected $fillable = [
        'id_notifikasi',
        'id_akun',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
