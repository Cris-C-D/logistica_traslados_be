<?php
use ApiEntregas\Controllers\Ubicacion;
use ApiEntregas\Controllers\Traslado;
use ApiEntregas\Controllers\Transporte;
use ApiEntregas\Controllers\Usuario;
use ApiEntregas\Controllers\Login;
use Bramus\Router\Router;

$router = new Router();

$router->get('/ubicaciones', function (){
    $ubicacion = new Ubicacion();
    $ubicacion->getUbicaciones();
});

$router->post('/ubicaciones/create', function (){
    $ubicacion = new Ubicacion();
    $ubicacion->create();
});

$router->put('/ubicaciones/edit', function (){
    $ubicacion = new Ubicacion();
    $ubicacion->edit();
});

$router->get('/traslados/tipos', function (){
    $traslado = new Traslado();
    $traslado->getTiposTraslado();
});

$router->post('/traslados/create', function (){
    $traslado = new Traslado();
    $traslado->create();
});

$router->post('/traslados/close', function(){
    $traslado = new Traslado();
    $traslado->close();
});

$router->get('/traslados', function (){
    $traslado = new Traslado();
    $traslado->getTraslados();
});

$router->get('/transportes', function (){
    $transporte = new Transporte();
    $transporte->getTransportes();
});

$router->post('/transportes/create', function (){
    $transporte = new Transporte();
    $transporte->create();
});

$router->put('/transportes/edit', function (){
    $transporte = new Transporte();
    $transporte->edit();
});

$router->get('/usuarios', function (){
    $usuario = new Usuario();
    $usuario->getUsuarios();
});

$router->get('/choferes', function (){
    $chofer = new Usuario();
    $chofer->getChoferes();
});

$router->post('/usuarios/create', function (){
    $usuario = new Usuario();
    $usuario->create();
});

$router->post('/login', function(){
    $login = new Login();
    $login->auth();
});

$router->set404(function(){
    echo json_encode(["message" => "404 not found"]);
});

$router->run();