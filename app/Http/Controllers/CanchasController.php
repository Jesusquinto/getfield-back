<?php

namespace App\Http\Controllers;
use App\Cancha;
use Illuminate\Support\Facades\DB;
use App\Reserva;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
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

      $url = [];
      for ($i=0; $i < 4 ; $i++) { 
         $file = Input::file('image'.$i);
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
            array_push($url, 'uploads/'.$nombre);
            $image = Image::make($file->getRealPath());
            $image->save($path);
      }




     



         $cancha = Cancha::create([
            'establecimiento_id' => $request->establecimiento_id,
            'dimensiones' => $request->dimensiones,
            'nombre' => $request->nombre,
            'imagen' => json_encode($url),
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


     
     function getBoundaries($lat, $lng, $distance, $earthRadius = 6371)
{
    $return = array();
     
    // Los angulos para cada dirección
    $cardinalCoords = array('north' => '0',
                            'south' => '180',
                            'east' => '90',
                            'west' => '270');
    $rLat = deg2rad($lat);
    $rLng = deg2rad($lng);
    $rAngDist = $distance/$earthRadius;
    foreach ($cardinalCoords as $name => $angle)
    {
        $rAngle = deg2rad($angle);
        $rLatB = asin(sin($rLat) * cos($rAngDist) + cos($rLat) * sin($rAngDist) * cos($rAngle));
        $rLonB = $rLng + atan2(sin($rAngle) * sin($rAngDist) * cos($rLat), cos($rAngDist) - sin($rLat) * sin($rLatB));
         $return[$name] = array('lat' => (float) rad2deg($rLatB), 
                                'lng' => (float) rad2deg($rLonB));
    }
    return array('min_lat'  => $return['south']['lat'],
                 'max_lat' => $return['north']['lat'],
                 'min_lng' => $return['west']['lng'],
                 'max_lng' => $return['east']['lng']);
}



     function get_canchas_location(Request $request){

      $lat  =  $request->lat;
      $lng =  $request->lng;
      $distance = $request->dist; // Sitios que se encuentren en un radio de 1KM
      $box = $this->getBoundaries($lat, $lng, $distance);
      $stmt = DB::select('SELECT canchas.id ,canchas.nombre, canchas.descripcion, canchas.imagen, canchas.dimensiones , canchas.parametros, establecimientos.nombre as establecimientoNombre, establecimientos.descripcion as establecimientoDescripcion  ,establecimientos.valoracion as establecimientoValoracion , establecimientos.horario as horarioEstablecimiento,establecimientos.lat, establecimientos.lng, establecimientos.id as establecimientoid , (6371 * ACOS( 
                                            SIN(RADIANS(lat)) 
                                            * SIN(RADIANS(' . $lat . ')) 
                                            + COS(RADIANS(lng - ' . $lng . ')) 
                                            * COS(RADIANS(lat)) 
                                            * COS(RADIANS(' . $lat . '))
                                            )
                               ) AS distancia
                     FROM establecimientos INNER JOIN (canchas)
                     ON (establecimientos.id = canchas.establecimiento_id)
                     WHERE canchas.estado = "A" and (lat BETWEEN ' . $box['min_lat']. ' AND ' . $box['max_lat'] . ')
                     AND (lng BETWEEN ' . $box['min_lng']. ' AND ' . $box['max_lng']. ')
                     HAVING distancia  < ' . $distance . '                                       
                     ORDER BY distancia ASC');

       return response()->json($stmt);


     }



















 }



     



   

