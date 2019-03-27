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
      


      $timestamp = new \DateTime();
      $result = $timestamp->format("Y-m-d H:i:s");
      $horaactual = new \Carbon\Carbon($result);
  
      $horaactual = $horaactual->addHours(6);
      


      echo $horainicial;
      echo "\n";
      echo "es menor o igual que";
      echo "\n";
      echo $horaactual;
      echo "\n";
      if($horainicial->lte($horaactual)) {

         echo "yes";

      }else{
         echo "no";
      }




//      $horainicial = new \DateTime( $request->horario['horainicial']);
  //    $horafinal = new \DateTime( $request->horario['horafinal']);

     // $actual =  new \DateTime();

      //$bash = $horafinal->diffInDays($horainicial);  
      
      //$result = $bash->format("Y-m-d H:i:s");
      //echo $result;


     //	$reservas = Reserva::create($request->all());
   
     }

}
