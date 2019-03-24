<?php

namespace App\Http\Controllers;
use App\Cancha;

class CanchasController extends Controller
{
     function get_canchas(){
        $canchas = Cancha::all();
        return response()->json($canchas);
     }

     //metodo listar canchas con establecimiento
     /*function get_canchas()
     {
     	$canchas = Cancha::with(['establecimientos'])->get();
     	return response()->json($canchas);
     }*/

     //metodo listar canchas con establecimiento por id
     function get_cancha_by_id($id)
     {
     	$canchas = Cancha::with(['establecimientos'])->where('id',$id)->get();
     	return response()->json($canchas);
     }

     //metodo listar canchas con establecimiento por id
     function crear_cancha(Request $request)
     {
     	$canchas = Cancha::create($request->all());
     	return response()->json($canchas, 201);
     }
}
