<?php

namespace App\Http\Controllers;
use App\Reserva;

class ReservasController extends Controller
{
     /*function get_reservas(){
        $reservas = Reserva::all();
        return response()->json($reservas);
     }*/

     function get_reservas()
     {
     	$reservas = Reserva::with(['usuario','cancha'])->get();
     	return response()->json($reservas);
     }

     //metodo listar canchas con establecimiento por id
     function get_reserva_by_id($id)
     {
     	$reservas = Reserva::with(['usuario','cancha'])->where('id',$id)->get();
     	return response()->json($reservas);
     }

     //metodo listar canchas con establecimiento por id
     function crear_reserva(Request $request)
     {
     	$reservas = Reserva::create($request->all());
     	return response()->json($reservas, 201);
     }

}
