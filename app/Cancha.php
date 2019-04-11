<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Cancha extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'canchas';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'id','establecimiento_id','dimensiones','nombre','imagen','descripcion','estado','parametros'
            ];
    protected $hidden = [
    ];


    //Una cancha pertenece a un establecimiento
    public function establecimiento()
    {
    	return $this->belongsTo(Establecimiento::class);
    }


    //Una cancha tiene varias reservas
    public function reserva()
    {
    	return $this->hasMany(Reserva::class);
    }
   


}
