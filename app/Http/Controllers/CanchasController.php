<?php

namespace App\Http\Controllers;
use App\Cancha;
use Illuminate\Support\Facades\DB;
use App\Reserva;

class CanchasController extends Controller
{


      //metodo listar canchas por establecimiento
     function get_canchas_by_establecimiento($establecimiento_id){
      $canchas = Cancha::with(['establecimiento'])->where('establecimiento_id',$establecimiento_id)->get();
      return response()->json($canchas);
   }




     //metodo listar canchas con estblecimientos
     function get_canchas(){
        $canchas = Cancha::with(['establecimiento'])->get();
        return response()->json($canchas);
     }

    
     //metodo listar canchas con establecimiento por id
     function get_cancha_by_id($id)
     {
     	$canchas = Cancha::with(['establecimiento'])->where('id',$id)->get();
     	return response()->json($canchas);
     }


     //metodo listar canchas con establecimiento por id
     function crear_cancha(Request $request)
     {
     	$canchas = Cancha::create($request->all());
     	return response()->json($canchas, 201);
     }
}

