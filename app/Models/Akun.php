<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Role;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Akun extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes, HasApiTokens, Notifiable;

    protected $primaryKey = "id_akun";
    protected $table = "akuns";

    protected $fillable = [
        'id_akun',
        'email',
        'password',
        'id_role',
        'profile_image',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
