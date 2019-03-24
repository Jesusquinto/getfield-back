<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
class Precio extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'precios';
    use Authenticatable, Authorizable;
    protected $fillable = [
        'monto','producto_id'    ];

    protected $hidden = [
        'producto_id'
    
    ];

    public function producto(){

        return $this->belongsTo(Producto::class);
    }


}
