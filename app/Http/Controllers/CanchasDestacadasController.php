<?php

namespace App\Http\Controllers;
use App\CanchasDestacadas;
use Illuminate\Support\Facades\DB;
use App\Reserva;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
class CanchasDestacadasController extends Controller
{


   



     //metodo listar canchas destacadas
     function get_canchasDestacadas(){
        $canchas = CanchasDestacadas::with(['cancha', 'cancha.establecimiento'])->get();
        foreach($canchas as $c){
           $c->cancha->parametros = json_decode($c->cancha->parametros);
           if($c->cancha->parametros->descuento != null){
            $c->cancha->parametros->precio = $c->cancha->parametros->valorhora - (($c->cancha->parametros->valorhora * $c->cancha->parametros->descuento) /100);
         }else{
            $c->cancha->parametros->precio = $c->cancha->parametros->valorhora;
         }
           $c->cancha->imagen = json_decode($c->cancha->imagen);
        }


        return response()->json($canchas);
     }




    
     





 }



     



   

