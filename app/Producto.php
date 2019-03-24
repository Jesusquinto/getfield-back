<?php
namespace App;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
class Producto extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'productos';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'nombre'    ];

    protected $hidden = [
        
    ];
    public function precios(){

        return $this->hasMany(Precio::class);
    }

    public function presentaciones(){

        return $this->hasMany(Presentacion::class);
    }

}
