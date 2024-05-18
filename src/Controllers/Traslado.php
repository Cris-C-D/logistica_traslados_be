<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\TrasladoModel;

class Traslado extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTiposTraslado()
    {
        $this->response(["tiposTraslados" => TrasladoModel::getTiposTraslado()]);
    }

    public function getTraslados()
    {
        $idUsuario = (isset($_GET['idUsuario']) && $_REQUEST['idUsuario'] !=NULL)?$_GET['idUsuario']:0;
        $idOrigen = (isset($_GET['idOrigen']) && $_REQUEST['idOrigen'] !=NULL)?$_GET['idOrigen']:0;
        $idDestino = (isset($_GET['idDestino']) && $_REQUEST['idDestino'] !=NULL)?$_GET['idDestino']:0;
        $idTransporte = (isset($_GET['idTransporte']) && $_REQUEST['idTransporte'] !=NULL)?$_GET['idTransporte']:0;
        $this->response(["traslados" => TrasladoModel::get($idUsuario, $idOrigen, $idDestino, $idTransporte)]);
    }

    public function create()
    {
        $this->exists(['idUsuario', 'idUbicacionOrigen', 'idUbicacionDestino', 'idTransporte', 'idTipoTraslado', 'fechaInicio', 'fechaFin']);
        $traslado = new TrasladoModel();
        $traslado->setIdUsuario($this->data['idUsuario']);
        $traslado->setIdUbicacionOrigen($this->data['idUbicacionOrigen']);
        $traslado->setIdUbicacionDestino($this->data['idUbicacionDestino']);
        $traslado->setIdTransporte($this->data['idTransporte']);
        $traslado->setIdTipoTraslado($this->data['idTipoTraslado']);
        $traslado->setFechaInicio($this->data['fechaInicio']);
        $traslado->setFechaFin($this->data['fechaFin']);

        $this->response($traslado->save());
    }
}