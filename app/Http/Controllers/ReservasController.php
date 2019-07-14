<?php

namespace App\Http\Controllers;
use App\Reserva;
use App\usuario;
use App\Cancha;
use App\Establecimiento;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\EstablecimientoController;
use App\Http\Controllers\CanchasController;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservasController extends Controller
{

     /*function get_reservas(){
        $reservas = Reserva::all();
        return response()->json($reservas);
     }*/


     //---@@metodo listar todas las reservas del usuario logueado--------------------------

     function get_reservas_usuario(){
      $user = JWTAuth::parseToken()->authenticate(); 
      $reservas = Reserva::with(['cancha','establecimiento'])->where('usuario_id', $user->id)->get();
      return response()->json($reservas);   

     }

    //---@@metodo listar todas las reservas con usuario y cancha--------------------------

     function get_reservas()
     {
     	$reservas = Reserva::with(['cancha','usuario'])->get();
     	return response()->json($reservas);
     }

     //---@@metodo listar reservas con usuario y cancha por id_reserva--------------------
     function get_reserva_by_id($id)
     {
     	$reservas = Reserva::with(['usuario','cancha'])->where('id',$id)->get();
     	return response()->json($reservas);
     }
      //---@@metodo listar reservas activas-------------------
     function get_reservas_activas()
     {
      $reservas = Reserva::with(['usuario', 'cancha', 'establecimiento'])->where('estado','A')->get();
      return response()->json($reservas);
     }
      //---@@metodo listar reservas inactivas-------------------
      function get_reservas_inactivas()
      {
       $reservas = Reserva::with(['usuario', 'cancha', 'establecimiento'])->where('estado',' IN')->get();
       return response()->json($reservas);
      }
     //---@@metodo listar reservas Activas por establecimiento-------------------
     function get_reservas_activas_by_establecimiento($establecimiento_id)
     {
        $reservas = Reserva::with(['usuario','cancha','establecimiento'])->where('establecimiento_id',$establecimiento_id)->where('estado','A')->get();
        return response()->json($reservas);
     }

     //---@@metodo listar reservas inactivas por establecimiento-------------------
     function get_reservas_inactivas_by_establecimiento($establecimiento_id)
     {
        $reservas = Reserva::with(['usuario','cancha','establecimiento'])->where('establecimiento_id',$establecimiento_id)->where('estado','IN')->get();
        return response()->json($reservas);
     }


     //---@@metodo listar rerservas -------------------
     function get_reservas_by_establecimiento($establecimiento_id)
     {
      $reservas = Reserva::with(['usuario', 'cancha', 'establecimiento'])->where('establecimiento_id', $establecimiento_id)->get();
      return response()->json($reservas);
     }



     //---@@metodo listar canchas mas reservadas activas -------------------
    function get_canchas_mas_reservadas(){
      $canchas = Reserva::with(['cancha','Establecimiento'])->select(DB::raw('reservas.cancha_id, canchas.establecimiento_id, count(*) as vecesReservada'))->join('canchas', 'reservas.cancha_id', '=', 'canchas.id')->where('reservas.estado','A')->groupBy('cancha_id')->orderBy('vecesReservada','desc')->get();
      return response()->json($canchas);
     }




     //---@@metodo listar usuarios mas frecuentes -------------------
     function get_usuarios_mas_frecuentes(){
      $usuarios = Reserva::with(['usuario'])->select(DB::raw('usuario_id,count(*) as vecesQueReserva'))->groupBy('usuario_id')->orderBy('vecesQueReserva','desc')->get();
      return response()->json($usuarios);
     }


     function cancelar_reserva_admin(request $request){
      $reserva= Reserva::find($request->id);
      $reserva->estado = 'CAA';
      $reserva->fecha_cancelada = new \Carbon\Carbon();;
      $reserva->save();
      return response()->json([
         'success' => [
            'title'=> 'Genial!',
             'code' => 200,
             'message' => "Reserva cancelada correctamente",
         ]
         ], 200); 
     }





//ERROR DE SEGURIDAD, VALIDAR EN UN FUTURO LA VERIFICACION DE PERTENENCIA DE LA RESERVA DE USUARIO POR TOKEN
     function cancelar_reserva(request $request){
      if($request->estado == 'A'){
         $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
         //Horario actual del servidor
         $horaactual = new \Carbon\Carbon();
         //Agregarle 2 horas a la fecha actual
         $horaactual->addHours(2);
         //VALIDACION: solo cancelar reservas con 2 horas de anticipacion
         if($horainicial->lt($horaactual)) {
            return response()->json([
               'error' => [
                  'title'=> 'Tiempo de anticipacion',
                   'code' => 400,
                   'message' => "Solo puede cancelar reservaciones 2 horas antes del inicio",
               ]
               ], 400);
         }
         $reserva= Reserva::find($request->id);
         $reserva->estado = 'CA';
         $reserva->fecha_cancelada = new \Carbon\Carbon();
         $reserva->save();
         return response()->json([
            'success' => [
               'title'=> 'Genial!',
                'code' =>200,
                'message' => "Reserva cancelada con exito",
            ]
            ], 200);
      }else{
         return response()->json([
            'error' => [
               'title'=> 'Error',
                'code' => 400,
                'message' => "Solo pueden cancelar reservaciones activas",
            ]
            ], 400);
      }

     }



     function editar_reserva(request $request){
      $reserva= Reserva::find($request->id);
      $reserva->estado = $request->estado;
      $reserva->horario = json_encode($request->horario);
      $reserva->metodo_pago = $request->metodo_pago;
      $reserva->valor_a_pagar = $request->valor_a_pagar;
      $reserva->save();
      return response()->json([
         'success' => [
            'title'=> 'success',
             'code' => 200,
             'message' => "Datos actualizados correctamente",
         ]
         ], 200);
     }










     //---@@metodo crear reserva (validacion de fechas)-------------------
     function crear_reserva(Request $request)
     {
      $valorapagar;

      $parametroscancha = Cancha::where('id',$request->cancha_id)->get();
      $parametroscancha = $parametroscancha[0]->parametros;
      $parametroscancha = json_decode($parametroscancha, true);



      $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
      $horafinal = new \Carbon\Carbon($request->horario['horafinal']);

      $horas =$horainicial->diffInMinutes($horafinal);

      if($horas < 15){
         return response()->json([
            'error' => [
               'title'=> 'Reserva muy corta',
                'code' => 400,
                'message' => "El lapso minimo de juego debe ser de 15 minutos",
            ]
            ], 400);
      }



      $horas = $horas/60;
      $valorporhora= $parametroscancha['valorhora'];
      $valorapagar = $horas*$valorporhora;

      
      $parametros = $request->parametros;

      if($parametros['jugadores'] > $parametroscancha['maxjugadores']){
         return response()->json([
            'error' => [
               'title'=> 'Muchos en la cancha',
                'code' => 400,
                'message' => "El maximo de jugadores ha sido exedido, la cancha solo admite a ".$parametroscancha['maxjugadores'].' jugadores como mucho',
            ]
            ], 400);
      }

      $valorapagar += ($parametros['jugadores'] * $parametroscancha['jugadores']);

      if($parametros['prestaball'] == 'true'){
            $valorapagar +=$parametroscancha['prestaball'];
      }

      if($parametros['prestatacos'] == 'true'){
         $valorapagar += ($parametroscancha['prestatacos']* $parametros['jugadores']);
      }
      if($parametros['prestapetos'] == 'true'){
         $valorapagar += ($parametroscancha['prestapetos']* $parametros['jugadores']);
      }




      //Hora inicial y final del cliente
      $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
      $horafinal = new \Carbon\Carbon($request->horario['horafinal']);
      //---------------------------------------------------------------------

      //Traer  el horario del establecimiento segun el id_establecimiento de la cancha del cliente (Mejorar: optimizar fragmento de codigo)
      $establecimiento = Cancha::with(['establecimiento'])->where('id',$request->cancha_id)->get();
      $establecimientohorario= json_decode($establecimiento[0]['establecimiento']->horario);


      //Horario actual del servidor
      $horaactual = new \Carbon\Carbon();
      //Agregarle 6 horas a la fecha actual
      $horaactual->addHours(6);

      //VALIDACION: solo aceptar reservas con 6 horas de anticipacion
      if($horainicial->lt($horaactual)) {
         return response()->json([
            'error' => [
               'title'=> 'Tiempo de anticipacion',
                'code' => 400,
                'message' => "El horario debe tener 6 horas de anticipacion como minimo",
            ]
            ], 400);

      }
       //VALIDACION: las reservas no deben superar el día

      //sacar solo el año, mes y dia de las fechas del cliente
      $dia = $horainicial->format('Y-m-d');
      $dia2 = $horafinal->format('Y-m-d');
    
      if($dia != $dia2){
         return response()->json([
            'error' => [
               'title'=> 'Maximo de tiempo',
                'code' => 400,
                'message' => "El tiempo de la reserva no debe superar el día",
            ]
            ], 400);
      }
     
      //VALIDACION: dias de la semana activos del establecimiento
      //sacar los dias de la hora inicial y final del cliente
      $dia = $horainicial->format('l');
      $dia2 = $horafinal->format('l');
      //Creo la flag error en verdadera
      $error = true;
      //recorrer todos los dias de la semana disponibles del establecimiento
      foreach($establecimientohorario->dias as $d){
      if($dia == $d && $dia2 == $d){
         //si el dia es igual a algun dia disponible del establecimiento, la flag error va a ser igual a falso
         $error = false;
      }
      }
      //Si al final del bucle la flag es positiva, retornar error 400
      if($error == true){
         return response()->json([
            'error' => [
               'title'=> 'Día no disponible',
                'code' => 400,
                'message' => "El día escogído no está disponible, seleccione otro",
            ]
            ], 400);
      }
      //VALIDACION: la fecha de reserva debe estar en el rango del horario de establecimientos
      //Hora final e inicial de las fechas dadas por el cliente
      $hi = new \Carbon\Carbon($horainicial);
      $hf = new \Carbon\Carbon($horafinal);
      //Hora final e inicial del horario del establecimiento
      $hi2 = new \Carbon\Carbon($horainicial->format('Y-m-d')." ".$establecimientohorario->horarios->horainicial);
      $hf2 = new \Carbon\Carbon($horafinal->format('Y-m-d')." ".$establecimientohorario->horarios->horafinal);

   
      //Si la reserva está entre el horario del establecimiento
      if($hi->between($hi2,$hf2)  &&  $hf->between($hi2,$hf2)){
         //VALIDACION: que este fuera rango de reservas del establecimiento
         //Traigo todas las reservas donde el id de la cancha sea igual al del cliente, así como el estado activo
         $reservas = Reserva::where('cancha_id',$request->cancha_id)->where('estado','A')->get();
            //Un bucle que recorra las reservas
            for($i =0;$i<sizeof($reservas);$i++){
               //Obtengo el horario de la reserva
               $reserva =  json_decode($reservas[$i]['horario'],true);
               $horainicial2 =  new \Carbon\Carbon( $reserva['horainicial']);
               $horafinal2 =  new \Carbon\Carbon($reserva['horafinal']);
               

               //Si las fechas del cliente estan en el rango del horario de la reserva
               if($horafinal->between($horainicial2,$horafinal2)  ||  $horainicial->between($horainicial2,$horafinal2)){
                  //responder un 400
                  return response()->json([
                     'error' => [
                        'title'=> 'Horario no disponible',
                         'code' => 400,
                         'message' => "El horario escogido no se encuentra disponible, escoja otro por favor",
                     ]
                     ], 400);
               }
            }

            //Si sale del for, todo esta ok (revisar)

               //'id_usuario','id_cancha','horario','estado','metodo_pago','valor_a_pagar'
            

            $data = $request->json()->all();
           
            $reservar = Reserva::create([
               'usuario_id' => $data['usuario_id'],
               'establecimiento_id' => $data['establecimiento_id'],
               'cancha_id' => $data['cancha_id'],
               'horario' => json_encode($data['horario']),
               'estado' => 'A',
               'metodo_pago' => $data['metodo_pago'],
               'valor_a_pagar' => $valorapagar,
               'estado_pago' => 'SP',
               'parametros' => json_encode($data['parametros'])
            ]);

            return response()->json([
               'success' => [
                  'title'=> 'Genial',
                   'code' => 201,
                   'message' => "Reservación creada con exito!",
               ]
               ], 201);


      }else{
         return response()->json([
            'error' => [
               'title'=> 'Horario del establecimiento',
                'code' => 400,
                'message' => "El horario del establecimiento no soporta ese horario",
            ]
            ], 400);
      }

     }




      //---@@metodo crear reserva (validacion de fechas)-------------------
      function crear_reserva_usuario_logueado(Request $request)
      {
         $user = JWTAuth::parseToken()->authenticate(); 
         $valorapagar;
   
         $parametroscancha = Cancha::where('id',$request->cancha_id)->get();
         $parametroscancha = $parametroscancha[0]->parametros;
         $parametroscancha = json_decode($parametroscancha, true);
   
   
   
         $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
         $horafinal = new \Carbon\Carbon($request->horario['horafinal']);
   
         $horas =$horainicial->diffInMinutes($horafinal);
   
         if($horas < 15){
            return response()->json([
               'error' => [
                  'title'=> 'Reserva muy corta',
                   'code' => 400,
                   'message' => "El lapso minimo de juego debe ser de 15 minutos",
               ]
               ], 400);
         }
   
   
   
         $horas = $horas/60;
         $valorporhora;
         if($parametroscancha['descuento'] != null){
         $valorporhora = $parametroscancha['valorhora'] - (($parametroscancha['valorhora'] * $parametroscancha['descuento']) / 100);

         }else{
            $valorporhora= $parametroscancha['valorhora'];
         };


         
         $valorapagar = $horas*$valorporhora;
   
         
         $parametros = $request->parametros;
   
  /*        if($parametros['jugadores'] > $parametroscancha['maxjugadores']){
            return response()->json([
               'error' => [
                  'title'=> 'Muchos en la cancha',
                   'code' => 400,
                   'message' => "El maximo de jugadores ha sido exedido, la cancha solo admite a ".$parametroscancha['maxjugadores'].' jugadores como mucho',
               ]
               ], 400);
         }
   
         $valorapagar += ($parametros['jugadores'] * $parametroscancha['jugadores']); */
   
         if($parametros['prestaball'] == 'true'){
               $valorapagar +=$parametroscancha['prestaball'];
         }
   
         if($parametros['prestatacos'] == 'true'){
            $valorapagar += ($parametroscancha['prestatacos']* $parametros['tacoscount']);
         }
         if($parametros['prestapetos'] == 'true'){
            $valorapagar += ($parametroscancha['prestapetos']* $parametros['petoscount']);
         }
   
   
   
   
         //Hora inicial y final del cliente
         $horainicial = new \Carbon\Carbon($request->horario['horainicial']);
         $horafinal = new \Carbon\Carbon($request->horario['horafinal']);
         //---------------------------------------------------------------------
   
         //Traer  el horario del establecimiento segun el id_establecimiento de la cancha del cliente (Mejorar: optimizar fragmento de codigo)
         $establecimiento = Cancha::with(['establecimiento'])->where('id',$request->cancha_id)->get();
         $establecimientohorario= json_decode($establecimiento[0]['establecimiento']->horario);
   
   
         //Horario actual del servidor
         $horaactual = new \Carbon\Carbon();
         //Agregarle 6 horas a la fecha actual
         $horaactual->addHours(6);
   
         //VALIDACION: solo aceptar reservas con 6 horas de anticipacion
         if($horainicial->lt($horaactual)) {
            return response()->json([
               'error' => [
                  'title'=> 'Tiempo de anticipacion',
                   'code' => 400,
                   'message' => "El horario debe tener 6 horas de anticipacion como minimo",
               ]
               ], 400);
   
         }
          //VALIDACION: las reservas no deben superar el día
   
         //sacar solo el año, mes y dia de las fechas del cliente
         $dia = $horainicial->format('Y-m-d');
         $dia2 = $horafinal->format('Y-m-d');
       
         if($dia != $dia2){
            return response()->json([
               'error' => [
                  'title'=> 'Maximo de tiempo',
                   'code' => 400,
                   'message' => "El tiempo de la reserva no debe superar el día",
               ]
               ], 400);
         }
        
         //VALIDACION: dias de la semana activos del establecimiento
         //sacar los dias de la hora inicial y final del cliente
         $dia = $horainicial->format('l');
         $dia2 = $horafinal->format('l');
         //Creo la flag error en verdadera
         $error = true;
         //recorrer todos los dias de la semana disponibles del establecimiento
         foreach($establecimientohorario->dias as $d){
         if($dia == $d && $dia2 == $d){
            //si el dia es igual a algun dia disponible del establecimiento, la flag error va a ser igual a falso
            $error = false;
         }
         }
         //Si al final del bucle la flag es positiva, retornar error 400
         if($error == true){
            return response()->json([
               'error' => [
                  'title'=> 'Día no disponible',
                   'code' => 400,
                   'message' => "El día escogído no está disponible, seleccione otro",
               ]
               ], 400);
         }
         //VALIDACION: la fecha de reserva debe estar en el rango del horario de establecimientos
         //Hora final e inicial de las fechas dadas por el cliente
         $hi = new \Carbon\Carbon($horainicial);
         $hf = new \Carbon\Carbon($horafinal);
         //Hora final e inicial del horario del establecimiento
         $hi2 = new \Carbon\Carbon($horainicial->format('Y-m-d')." ".$establecimientohorario->horarios->horainicial);
         $hf2 = new \Carbon\Carbon($horafinal->format('Y-m-d')." ".$establecimientohorario->horarios->horafinal);
   
      
         //Si la reserva está entre el horario del establecimiento
         if($hi->between($hi2,$hf2)  &&  $hf->between($hi2,$hf2)){
            //VALIDACION: que este fuera rango de reservas del establecimiento
            //Traigo todas las reservas donde el id de la cancha sea igual al del cliente, así como el estado activo
            $reservas = Reserva::where('cancha_id',$request->cancha_id)->where('estado','A')->get();
               //Un bucle que recorra las reservas
               for($i =0;$i<sizeof($reservas);$i++){
                  //Obtengo el horario de la reserva
                  $reserva =  json_decode($reservas[$i]['horario'],true);
                  $horainicial2 =  new \Carbon\Carbon( $reserva['horainicial']);
                  $horafinal2 =  new \Carbon\Carbon($reserva['horafinal']);
                  
   
                  //Si las fechas del cliente estan en el rango del horario de la reserva
                  if($horafinal->between($horainicial2,$horafinal2)  ||  $horainicial->between($horainicial2,$horafinal2)){
                     //responder un 400
                     return response()->json([
                        'error' => [
                           'title'=> 'Horario no disponible',
                            'code' => 400,
                            'message' => "El horario escogido no se encuentra disponible, escoja otro por favor",
                        ]
                        ], 400);
                  }
               }
   
               //Si sale del for, todo esta ok (revisar)
   
                  //'id_usuario','id_cancha','horario','estado','metodo_pago','valor_a_pagar'
               
   
               $data = $request->json()->all();
              
               $reservar = Reserva::create([
                  'usuario_id' => $user->id,
                  'establecimiento_id' => $data['establecimiento_id'],
                  'cancha_id' => $data['cancha_id'],
                  'horario' => json_encode($data['horario']),
                  'estado' => 'A',
                  'metodo_pago' => $data['metodo_pago'],
                  'valor_a_pagar' => $valorapagar,
                  'estado_pago' => 'SP',
                  'parametros' => json_encode($data['parametros'])
               ]);
   
               return response()->json([
                  'success' => [
                     'title'=> 'Genial',
                      'code' => 201,
                      'message' => "Reservación creada con exito!",
                  ]
                  ], 201);
   
   
         }else{
            return response()->json([
               'error' => [
                  'title'=> 'Horario del establecimiento',
                   'code' => 400,
                   'message' => "El horario del establecimiento no soporta ese horario",
               ]
               ], 400);
         }

      }












}
