<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Establecimiento extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'establecimientos';
    protected $tprimaryKey = 'id';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'nombre','descripcion','estado','horario','user','password','valoracion'
            ];
    protected $hidden = [
        'password'
    ];

    public function canchas()
    {
    	return $this->hasMany(Cancha::class);
    }

}
