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
    protected $primaryKey = 'id';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'id','id_establecimiento','dimensiones','nombre','imagen','descripcion','estado','parametros','valor_x_minuto'
            ];
    protected $hidden = [
    ];

    /*public function establecimientos()
    {
    	return $this->belongsTo('establecimientos','id');
    }*/

}
