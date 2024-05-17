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
        $this->response(["traslados" => TrasladoModel::get()]);
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