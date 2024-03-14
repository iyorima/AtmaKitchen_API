<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alamat extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = "id_alamat";

    protected $fillable = [
        'id_alamat',
        'id_pelanggan',
        'alamat',
    ];
}
