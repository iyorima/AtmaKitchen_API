<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Akun extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id_akun";

    protected $fillable = [
        'id_akun',
        'email',
        'password',
        'id_role',
        'profile_image',
        'email_verified_at',
        'remember_token',
    ];
}
