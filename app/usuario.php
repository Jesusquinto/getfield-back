<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Usuario extends Model implements JWTSubject, AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;
    protected $table = 'usuario';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'id','nombre','apellido','usuario','estado','email','password'
            ];
    protected $hidden = [
        'password'

    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


    //Un usuario tiene varias reservas
    public function Reserva()
    {
    	return $this->hasMany(Reserva::class);
    }

}
