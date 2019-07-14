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

        $data = $request;
        
        $usuario = usuario::create([
           'nombre' => $data['nombre'],
           'apellido' => $data['apellido'],
           'usuario' => $data['usuario'],
           'estado' => 'A',
           'email' => $data['email'],
           'rol_id' => 3,
           'password' =>Hash::make( $data['password'])
        ]);
          return response()->json($usuario, 201);
     }
   //--------------------------------------

      //medoto actualizar usuario logueado
    function actualizar_usuario(Request $request){
      $user = JWTAuth::parseToken()->authenticate();
      $usuario= Usuario::find($user->id);
      $usuario->nombre = $request->nombre;
      $usuario->apellido =  $request->apellido;
      $usuario->usuario = $request->usuario;
      $usuario->password = Hash::make( $request->password); 
      $usuario->save();
      return response()->json([
         'success' => [
            'title'=> 'Genial!',
             'code' => 200,
             'message' => "Datos actualizados correctamente",
         ]
         ], 200);
   
     }
     







    //medoto actualizar usuario admin
    function actualizar_usuario_admin(Request $request){
      $usuario= Usuario::find($request->id);
      $usuario->nombre = $request->nombre;
      $usuario->apellido =  $request->apellido;
      $usuario->usuario = $request->usuario;
      $usuario->password = $request->password; 
      $usuario->save();
      return response()->json([
         'success' => [
            'title'=> 'success',
             'code' => 200,
             'message' => "Datos actualizados correctamente",
         ]
         ], 200);
   
     }


     function usuarios_mas_frecuentes(){
            



     }




}
