<?php

namespace App\Http\Controllers;
use App\Establecimiento;
use App\Cancha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
class EstablecimientoController extends Controller
{
	 //metodo listar todas los establecimientos
     function get_establecimientos(){
        $establecimiento = Establecimiento::with(['canchas'])->get();
       
        return response()->json($establecimiento);
     }

     //metodo listar establecimiento por id
     function get_establecimiento_by_id($id){
        $establecimiento = Establecimiento::with(['canchas'])->where('id',$id)->get();
        return response()->json($establecimiento);
     }






     //medoto crear establecimiento
     function crear_establecimiento(Request $request){
         $datos = Establecimiento::where('nombre',$request->nombre)->get();
         if(sizeof($datos) != 0){
            return response()->json([
               'error' => [
                  'title'=> 'Error',
                   'code' => 400,
                   'message' => "El nombre ".$request->nombre." ya esta en uso por otro establecimiento!",
               ]
               ], 400);
         } 
         $file = Input::file('image');
         $datos = $file->getClientOriginalName();
         $datos = $splitName = explode('.', $datos, 3);
         $datos = $datos[sizeof($datos)-1];
         if($datos != 'jpg'){
            return response()->json([
               'error' => [
                  'title'=> 'Error',
                   'code' => 400,
                   'message' => "La extension ".$datos." no es un formato disponible, solo jpg!",
               ]
               ], 400);
         }
         $random = str_random(10);
         $nombre = $random.'-'.$request->nombre.'-'.$file->getClientOriginalName();
         $path = rtrim(app()->basePath('public/' . 'uploads/'.$nombre), '/');
         $url = 'uploads/'.$nombre;
         $image = Image::make($file->getRealPath());
         $image->save($path);
         $establecimiento = Establecimiento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'logo_img' => $url,
            'estado' => 'A',
            'horario' => $request->horario,
            'user' => $request->user,
            'password' => Hash::make( $request->password),
            'lat' => $request->latitud,
            'lng' => $request->longitud,
            'valoracion' => 10
         ]);
         return response()->json([
            'success' => [
               'title'=> 'Genial',
                'code' => 201,
                'message' => "Establecimiento ".$request->nombre." creado con exito!",
            ]
            ], 201);
     }

     //medoto actualizar establecimiento
     function actualizar_establecimiento(Request $request){
      $reserva= Establecimiento::find($request->id);
      $reserva->nombre = $request->nombre;
      $reserva->descripcion =  $request->descripcion;
      $reserva->estado = $request->estado;
      $reserva->horario = $request->horario;
      $reserva->user = $request->user;
      $reserva->password = Hash::make( $request->password);
      $reserva->lat = $request->lat;
      $reserva->lng = $request->lng;
      $reserva->valoracion = $request->valoracion;


      $reserva->save();
      return response()->json([
         'success' => [
            'title'=> 'success',
             'code' => 200,
             'message' => "Datos actualizados correctamente",
         ]
         ], 200);
     }
     
     

}
