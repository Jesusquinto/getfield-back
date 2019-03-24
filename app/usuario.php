<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Usuario extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'usuario';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'nombre','password'
            ];
    protected $hidden = [
        'password'

    ];

}
