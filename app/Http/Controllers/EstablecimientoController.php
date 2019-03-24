<?php

namespace App\Http\Controllers;
use App\Establecimiento;

class EstablecimientoController extends Controller
{
	 //metodo listar todas los establecimientos
     function get_establecimientos(){
        $establecimiento = Establecimiento::all();
        return response()->json($establecimiento);
     }

     //metodo listar establecimiento por id
     function get_establecimiento_by_id($id){
        $establecimiento = Establecimiento::find($id);
        return response()->json($establecimiento);
     }

     //medoto crear establecimiento
     function crear_establecimiento(Request $request){
        $establecimiento = Establecimiento::create($request->all());
        return response()->json($establecimiento, 201);
     }

     //medoto actualizar establecimiento
     function actualizar_establecimiento($id, Request $request){
        $establecimiento = Establecimiento::findOrFail($id);
        $establecimiento->update($request->all());
        return response()->json($establecimiento, 200);
     }

}
