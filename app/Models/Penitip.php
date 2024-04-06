<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penitip extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing=false;
    protected $primaryKey = "id_penitip";

    protected $fillable = [
        'id_penitip',
        'nama',
        'telepon',
        'alamat'
    ];
}
