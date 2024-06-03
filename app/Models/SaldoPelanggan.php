<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaldoPelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_saldo_pelanggan";

    protected $fillable = [
        'id_saldo_pelanggan',
        'id_akun',
        'id_pesanan',
        'saldo',
        'total_saldo',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public static function saldoPelangganLatest()
    {
        return self::orderBy('created_at', 'desc')
            ->get()
            ->unique('id_akun')
            ->values();
    }
}
