<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Auth;
use ApiEntregas\Models\TrasladoModel;

class Traslado extends Auth
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
        $idUbicacionOrigen = (isset($_GET['idUbicacionOrigen']) && $_REQUEST['idUbicacionOrigen'] !=NULL)?$_GET['idUbicacionOrigen']:0;
        $idUbicacionDestino = (isset($_GET['idUbicacionDestino']) && $_REQUEST['idUbicacionDestino'] !=NULL)?$_GET['idUbicacionDestino']:0;
        $idTransporte = (isset($_GET['idTransporte']) && $_REQUEST['idTransporte'] !=NULL)?$_GET['idTransporte']:0;
        $open = (isset($_GET['open']) && $_REQUEST['open'] !=NULL)?$_GET['open']:0;
        $fechaInicio = (isset($_GET['fechaInicio']) && $_REQUEST['fechaInicio'] !=NULL)?$_GET['fechaInicio']:'';
        $fechaFin = (isset($_GET['fechaFin']) && $_REQUEST['fechaFin'] !=NULL)?$_GET['fechaFin']:'';
        $this->response(["traslados" => TrasladoModel::get($idUsuario, $idUbicacionOrigen, $idUbicacionDestino, $idTransporte, $open, $fechaInicio, $fechaFin)]);
    }

    public function create()
    {
        $this->exists(['idUsuario', 'idUbicacionOrigen', 'idUbicacionDestino', 'idTransporte', 'idTipoTraslado']);
        $traslado = new TrasladoModel();
        $traslado->setIdUsuario($this->data['idUsuario']);
        $traslado->setIdUbicacionOrigen($this->data['idUbicacionOrigen']);
        $traslado->setIdUbicacionDestino($this->data['idUbicacionDestino']);
        $traslado->setIdTransporte($this->data['idTransporte']);
        $traslado->setIdTipoTraslado($this->data['idTipoTraslado']);
        $traslado->setFechaInicio(date("Y-m-d h:i:s"));

        $this->response($traslado->save());
    }

    public function close()
    {
        $this->exists(['idTraslado']);
        $traslado = new TrasladoModel();
        $traslado->setId($this->data['idTraslado']);
        $traslado->setFechaFin(date("Y-m-d h:i:s"));
        $traslado->setComentario($this->data['comentario']);

        $this->response($traslado->close());
    }
}