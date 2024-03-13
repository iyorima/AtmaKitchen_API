<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;
    protected $primaryKey = "id_alamat";

    protected $fillable = [
        'id_alamat',
        'id_pelanggan',
        'alamat',
    ];


}
