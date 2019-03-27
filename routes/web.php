<?php
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('saludo', function(){
	return response()->json();
});
//$router->get('productos',  [ 'uses' => 'ProductosController@get_productos']);

//@get usuarios
$router->get('usuarios',  [ 'uses' => 'UsuarioController@get_usuarios']);
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
//@post crear establecimiento
$router->post('establecimientos',  [ 'uses' => 'EstablecimientoController@crear_establecimiento']);
//@put actualizar establecimiento
$router->put('establecimientos',  [ 'uses' => 'EstablecimientoController@actualizar_establecimiento']);

//@get canchas
$router->get('canchas',  [ 'uses' => 'CanchasController@get_canchas']);
//@get canchas 
$router->get('canchas/{id}',  [ 'uses' => 'CanchasController@get_cancha_by_id']);
//@post canchas 
$router->post('canchas',  [ 'uses' => 'CanchasController@create_cancha']);

//@get reservas
$router->get('reservas',  [ 'uses' => 'ReservasController@get_reservas']);
//@get reservas
$router->get('reservas/{id}',  [ 'uses' => 'ReservasController@get_reserva_by_id']);
//
$router->post('reservas',  [ 'uses' => 'ReservasController@crear_reserva']);

