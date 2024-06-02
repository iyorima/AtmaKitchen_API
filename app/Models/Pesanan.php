<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Pelanggan;
use App\Models\DetailPesanan;
use App\Models\Pengiriman;
use App\Models\Produk;
use App\Models\StatusPesanan;


class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_pesanan";
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_metode_pembayaran',
        'id_pelanggan',
        'tgl_order',
        'total_diskon_poin',
        'total_pesanan',
        'total_setelah_diskon',
        'total_dibayarkan',
        'total_tip',
        'bukti_pembayaran',
        'jenis_pengiriman',
        'verified_at',
        'accepted_at',
    ];

    public function detail_pesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan');
    }
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function produk()
    {
        return $this->hasMany(Produk::class, DetailPesanan::class, 'id_pesanan', 'id_produk');
    }

    public function status_pesanan()
    {
        return $this->hasMany(StatusPesanan::class, 'id_pesanan');
    }

    public function status_pesanan_latest()
    {
        return $this->hasOne(StatusPesanan::class, 'id_pesanan')->latestOfMany('created_at');
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class, 'id_pesanan');
    }

    public function id_metode_pembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'id_metode_pembayaran');
    }

    public function poins()
    {
        return $this->hasOne(Poin::class, 'id_pesanan');
    }

    /**
     * Calculate the points earned from a pesanan (order).
     *
     * @return int The total points earned.
     */
    public function calculate_poin()
    {
        $points = 0;
        $total = $this->total_pesanan;

        $points += intdiv($total, 1000000) * 200;
        $total %= 1000000;

        $points += intdiv($total, 500000) * 75;
        $total %= 500000;

        $points += intdiv($total, 100000) * 15;
        $total %= 100000;

        $points += intdiv($total, 10000) * 1;

        // Handle double points if tgl_order is within 3 days after and before the customer's birthday.
        $customerBirthday = $this->pelanggan->tgl_lahir;
        $tglOrder = $this->tgl_order;

        $customerBirthday = date('Y') . substr($customerBirthday, 4); // Set the year of the customer's birthday to the current year for comparison.
        $customerBirthday = date_create($customerBirthday);
        $tglOrder = date_create($tglOrder);

        $diff = date_diff($customerBirthday, $tglOrder);
        $daysDifference = $diff->days;

        if ($daysDifference >= -3 && $daysDifference <= 3) {
            $points *= 2;
        }

        return ['poin' => $points, 'is_double_poin' => $daysDifference >= -3 && $daysDifference <= 3];
    }
}
