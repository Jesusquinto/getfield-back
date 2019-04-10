<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class Reserva extends Model implements AuthenticatableContract, AuthorizableContract
{
    

    protected $table = 'reservas';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'usuario_id','cancha_id','establecimiento_id','horario','estado','metodo_pago','valor_a_pagar'
            ];
    protected $hidden = [

    ];

   //Una reserva pertenece a una cancha
    public function cancha()
    {
    	return $this->belongsTo(Cancha::class);
    }


    //Una reserva pertenece a un establecimiento
    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);

    }

    //Una reserva pertenece a un usuario
    public function usuario()
    {
    	return $this->belongsTo(Usuario::class);
    }




}
