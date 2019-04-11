<?php

namespace App\Http\Controllers;
use App\Cancha;
use Illuminate\Support\Facades\DB;
use App\Reserva;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
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

      //medoto actualizar canchas
     function actualizar_cancha(Request $request){

      $cancha= Cancha::find($request->id);
      $cancha->establecimiento_id = $request->establecimiento_id;
      $cancha->dimensiones =  $request->dimensiones;
      $cancha->nombre = $request->nombre;
      $cancha->descripcion = $request->descripcion;
      $cancha->parametros = $request->parametros;
      $cancha->estado = $request->estado;



      $cancha->save();
      return response()->json([
         'success' => [
            'title'=> 'success',
             'code' => 200,
             'message' => "Datos actualizados correctamente",
         ]
         ], 200);
     


     }




     //metodo crear canchas con imagen
     function crear_cancha(Request $request)
     {
      $datos = Cancha::where('nombre',$request->nombre)->get();
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
         $cancha = Cancha::create([
            'establecimiento_id' => $request->establecimiento_id,
            'dimensiones' => $request->dimensiones,
            'nombre' => $request->nombre,
            'imagen' => $url,
            'descripcion' => $request->descripcion,
            'estado' => 'A',
            'parametros' => $request->parametros,
            
         ]);
         return response()->json([
            'success' => [
               'title'=> 'Genial',
                'code' => 201,
                'message' => "Establecimiento ".$request->nombre." creado con exito!",
            ]
            ], 201);
     }






 }



     



   

