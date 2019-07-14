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
      'id', 'nombre','descripcion','estado','horario','lat','lng','logo_img','valoracion'
            ];
    protected $hidden = [
        
    ];
    //Un establecimiento tiene muchas canchas
    public function canchas()
    {
    	return $this->hasMany(Cancha::class);
    }
    //Un establecimiento tiene muchas reservas
    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }


}
