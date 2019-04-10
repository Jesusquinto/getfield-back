<?php

namespace App\Http\Controllers;
use App\usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
class UsuarioController extends Controller
{
	 //metodo listar todas los usuarios
   function get_usuarios(){
       $usuario = usuario::all();
       return response()->json($usuario);
     }

     //Mtetodo obtener el usuario logueado
     function get_usuario(Request $request){
      $user = JWTAuth::parseToken()->authenticate();
      $usuario = usuario::find($user->id);
      return response()->json($usuario);
     }
     

     //metodo listar usuario por id
     function get_usuario_by_id($id){
        $usuario = usuario::find($id);
        return response()->json($usuario);
     }

     //medoto crear usuario----------------------------------
     function crear_usuario(Request $request){
        $data = $request->json()->all();
        $usuario = usuario::create([
           'nombre' => $data['nombre'],
           'apellido' => $data['apellido'],
           'usuario' => $data['usuario'],
           'estado' => 'A',
           'email' => $data['email'],
           'password' =>Hash::make( $data['password'])
        ]);
          return response()->json($usuario, 201);
     }
   //--------------------------------------


     







     //medoto actualizar usuario
     function actualizar_usuario($id, Request $request){
        $usuario = usuario::findOrFail($id);
        $usuario->update($request->all());
        return response()->json($usuario, 200);
     }


     function usuarios_mas_frecuentes(){
            



     }




}
