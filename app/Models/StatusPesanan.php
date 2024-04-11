<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
class StatusPesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_status_pesanan";

    protected $fillable = [
        'id_status_pesanan',
        'id_pesanan',
        'id_karyawan',
        'status',
    ];

    public function pesanan()
{
    return $this->belongsTo(Pesanan::class, 'id_pesanan');
}

public function detailPesanans()
{
    return $this->hasManyThrough(DetailPesanan::class, Pesanan::class, 'id_pelanggan', 'id_pesanan');
}



}
