<?php

namespace App\Http\Controllers;
use App\Usuario;

class UsuarioController extends Controller
{
	 //metodo listar todas los usuarios
     function get_usuarios(){
        $usuario = Usuario::all();
        return response()->json($usuario);
     }

     //metodo listar usuario por id
     function get_usuario_by_id($id){
        $usuario = Usuario::find($id);
        return response()->json($usuario);
     }

     //medoto crear usuario
     function crear_usuario(Request $request){
        $usuario = Usuario::create($request->all());
        return response()->json($usuario, 201);
     }

     //medoto actualizar usuario
     function actualizar_usuario($id, Request $request){
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());
        return response()->json($usuario, 200);
     }
}
