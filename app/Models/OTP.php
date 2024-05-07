<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;
    protected $primaryKey = "id";
    protected $table = 'otps';

    protected $fillable = ['user_id', 'code', 'expires_at'];

    public static function generate($userId)
    {
        $otp = static::where('user_id', $userId)->first();

        if (!$otp) {
            $otp = new static;
            $otp->user_id = $userId;
        }

        $otp->code = mt_rand(1000, 9999); // Generate random 4-digit OTP
        $otp->expires_at = now()->addMinutes(10); // OTP akan kedaluwarsa dalam 10 menit
        $otp->save();

        return $otp;
    }
}
