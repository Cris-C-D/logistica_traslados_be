<?php
use ApiEntregas\Controllers\Ubicacion;
use ApiEntregas\Controllers\Traslado;
use ApiEntregas\Controllers\Transporte;
use Bramus\Router\Router;

$router = new Router();

$router->get('/ubicaciones', function (){
    $ubicacion = new Ubicacion();
    $ubicacion->getUbicaciones();
});

$router->get('/traslados/tipos', function (){
    $traslado = new Traslado();
    $traslado->getTiposTraslado();
});

$router->post('/traslados/create', function (){
    $traslado = new Traslado();
    $traslado->create();
});

$router->get('/traslados', function (){
    $traslado = new Traslado();
    $traslado->getTraslados();
});

$router->get('/transportes', function (){
    $transporte = new Transporte();
    $transporte->getTransportes();
});

$router->set404(function(){
    echo json_encode(["message" => "404 not found"]);
});

$router->run();