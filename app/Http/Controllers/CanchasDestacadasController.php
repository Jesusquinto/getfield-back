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
        return response()->json($canchas);
     }




    
     





 }



     



   

