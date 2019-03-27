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
        'id','id_usuario','id_cancha','horario','estado','metodo_pago','valor_a_pagar','fecha_reserva','fecha_cancelada'
            ];
    protected $hidden = [

    ];

   //Una reserva pertenece a una cancha
    public function cancha()
    {
    	return $this->belongsTo(Cancha::class);
    }

    //Una reserva pertenece a un usuario
    public function usuario()
    {
    	return $this->belongsTo(Usuario::class);
    }




}
