<?php

namespace App\Http\Controllers;
use App\Reserva;
use App\usuario;
use App\Cancha;
use Illuminate\Http\Request;
use Carbon\Carbon;



class ReservasController extends Controller
{
     /*function get_reservas(){
        $reservas = Reserva::all();
        return response()->json($reservas);
     }*/


  

     function get_reservas()
     {
     	$reservas = Reserva::with(['cancha','usuario'])->get();
     	return response()->json($reservas);
     }

     //metodo listar canchas con establecimiento por id
     function get_reserva_by_id($id)
     {
     	$reservas = Reserva::with(['usuario','cancha'])->where('id',$id)->get();
     	return response()->json($reservas);
     }

     //metodo crear reserva (validacion de fechas)
     function crear_reserva(Request $request)
     {
      $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
      $horafinal = new \Carbon\Carbon($request->horario['horafinal']);
      
      $horaactual = new \Carbon\Carbon();
      $horaactual->addHours(6);
    
      if($horainicial->lt($horaactual)) {
         return response()->json([
            'error' => [
               'title'=> 'Tiempo de anticipacion',
                'code' => 400,
                'message' => "El horario debe tener 6 horas de anticipacion como minimo",
            ]
            ], 400);

      }else{
            $reservas = Reserva::with(['cancha','usuario'])->get();

            for($i =0;$i<sizeof($reservas);$i++){
               $reserva =  json_decode($reservas[$i]['horario'],true);
               $horainicial2 =  new \Carbon\Carbon( $reserva['horainicial']);
               $horafinal2 =  new \Carbon\Carbon($reserva['horafinal']);
               
               echo "\n";
               echo "Hora inicial";
               echo "\n";
               echo $horainicial2;
               echo "\n";;
               echo "Hora final";
               echo "\n";
               echo $horafinal2;
               echo "\n";

               if($horafinal->between($horainicial2,$horafinal2)  ||  $horainicial->between($horainicial2,$horafinal2)){
                  
                  return response()->json([
                     'error' => [
                        'title'=> 'Horario no disponible',
                         'code' => 400,
                         'message' => "El horario escogido no se encuentra disponible, escoja otro por favor",
                     ]
                     ], 400);

               }

            }
      }




     // $horainicial2 = new \Carbon\Carbon($r->horario['horainicial']);
     // $horafinal2 = new \Carbon\Carbon($r->horario['horafinal']);

     // if($horainicial->gt($horainicial2) && $horafinal->lt($horafinal2)){
         
     // }

     // if($horainicial->between($horainicial2,$horafinal2)){
      //     echo "NOOOOOOOOOOOOOOOOOOOOOOOO";

     // }
      //--------------------------------------------------------------------


//      $horainicial = new \DateTime( $request->horario['horainicial']);
  //    $horafinal = new \DateTime( $request->horario['horafinal']);

     // $actual =  new \DateTime();

      //$bash = $horafinal->diffInDays($horainicial);  
      
      //$result = $bash->format("Y-m-d H:i:s");
      //echo $result;


     //	$reservas = Reserva::create($request->all());
     //------------------------------------------------------------------ 
     // return response()->json([
     //    'error' => [
     //        'code' => 201,
     //        'message' => "Usted es pvto",
      //   ]
       //  ], 404);

     }

}
