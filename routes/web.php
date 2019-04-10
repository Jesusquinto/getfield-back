<?php
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('saludo', function(){
	return response()->json();
});
//$router->get('productos',  [ 'uses' => 'ProductosController@get_productos']);

//@get usuarios
//$router->get('usuarios',  [ 'uses' => 'UsuarioController@get_usuarios']);
//@get usuarios
$router->get('usuarios/{id}',  [ 'uses' => 'UsuarioController@get_usuario_by_id']);
//@post usuarios
$router->post('usuarios',  [ 'uses' => 'UsuarioController@crear_usuario']);
//@put usuarios
$router->put('usuarios/{id}',  [ 'uses' => 'UsuarioController@actualizar_usuario']);





//@get establecimientos
$router->get('establecimientos',  [ 'uses' => 'EstablecimientoController@get_establecimientos']);
//@get establecimiento_by_id
$router->get('establecimientos/{id}',  [ 'uses' => 'EstablecimientoController@get_establecimiento_by_id']);
//@put actualizar establecimiento
$router->put('establecimientos',  [ 'uses' => 'EstablecimientoController@actualizar_establecimiento']);

//@post crear establecimiento
$router->post('establecimientos',  [ 'uses' => 'EstablecimientoController@crear_establecimiento']);



//@get canchas
$router->get('canchas',  [ 'uses' => 'CanchasController@get_canchas']);
//@get canchas 
$router->get('canchas/{id}',  [ 'uses' => 'CanchasController@get_cancha_by_id']);
//@get canchas por id establecimiento
$router->get('canchasEstablecimiento/{establecimiento_id}', ['uses' => 'CanchasController@get_canchas_by_establecimiento']);

//@post canchas 
$router->post('canchas',  [ 'uses' => 'CanchasController@create_cancha']);






//@get reservas
$router->get('reservas',  [ 'uses' => 'ReservasController@get_reservas']);
//@get reservas
$router->get('reservas/{id}',  [ 'uses' => 'ReservasController@get_reserva_by_id']);
//@get reservas activas por establecimiento
$router->get('reservasAEstablecimiento/{establecimiento_id}', ['uses' => 'ReservasController@get_reservas_activas_by_establecimiento']);
//@get reservas inactivas por establecimiento
$router->get('reservasINEstablecimiento/{establecimiento_id}', ['uses' => 'ReservasController@get_reservas_inactivas_by_establecimiento']);
//@get reservas activas
$router->get('reservasActivas', ['uses' => 'ReservasController@get_reservas_activas']);
//@get reservas inactivas
$router->get('reservasInactivas', ['uses' => 'ReservasController@get_reservas_inactivas']);
//@get canchas mas reservadas
$router->get('canchasMasReservadas', ['uses' => 'ReservasController@get_canchas_mas_reservadas']);
//@get usuarios mas frecuentes
$router->get('usuariosMasFrecuentes', ['uses' => 'ReservasController@get_usuarios_mas_frecuentes']);    
//@get usuarios mas frecuentes
$router->get('ReservasEstablecimiento/{establecimiento_id}', ['uses' => 'ReservasController@get_reservas_by_establecimiento']); 

//@crear reserva
$router->post('reservas',  [ 'uses' => 'ReservasController@crear_reserva']);
//@cancelar reserva
$router->post('cancelarreserva',  [ 'uses' => 'ReservasController@cancelar_reserva']);
//@cancelar reserva admin
$router->post('cancelarreservaadmin',  [ 'uses' => 'ReservasController@cancelar_reserva_admin']);
//@editar reserva
$router->post('editarreserva', ['uses' => 'ReservasController@editar_reserva']);











//LOGIN
$router->post('/login', 'JwtAuthController@Login');


$router->group(['middleware'=>'auth:api'],function($router){
    $router->get('usuario',  [ 'uses' => 'UsuarioController@get_usuario']);
    


});


$router->post('googlesingup', 'googleAuthController@googleSingUp');
$router->post('googlesingin', 'googleAuthController@googleSingIn');