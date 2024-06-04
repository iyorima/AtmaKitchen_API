<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenarikanSaldo extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_penarikan_saldo";

    protected $fillable = [
        'id_akun',
        'jumlah_penarikan',
        'nama_bank',
        'nomor_rekening',
        'transfer_at',
        'status',
    ];

    public function akun(){
        return $this->hasOne(Akun::class, "id_akun");
    }
    public function pelanggan(){
        return $this->hasOne(Pelanggan::class, "id_akun");
    }
}
