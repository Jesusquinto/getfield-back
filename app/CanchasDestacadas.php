<?php
namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class CanchasDestacadas extends Model implements AuthenticatableContract, AuthorizableContract
{

    protected $table = 'canchas_destacadas';

    use Authenticatable, Authorizable;

    protected $fillable = [
        'id','cancha_id'
            ];
    protected $hidden = [
    ];


    public function cancha()
    {
    	return $this->belongsTo(Cancha::class, 'id');
    }




}
