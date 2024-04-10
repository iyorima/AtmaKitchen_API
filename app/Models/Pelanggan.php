<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Akun;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pelanggan";

    protected $fillable = [
        'id_pelanggan',
        'id_akun',
        'nama',
        'tgl_lahir',
        'telepon'
    ];

    public function id_akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun');
    }

    public function history_order()
    {
        return $this->hasMany(Pesanan::class, 'id_pelanggan');
    }
}
