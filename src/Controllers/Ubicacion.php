<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Auth;
use ApiEntregas\Models\UbicacionModel;

class Ubicacion extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUbicaciones()
    {
        $this->response(["ubicaciones" => UbicacionModel::getUbicaciones()]);
    }

    public function create()
    {
        $this->exists(['ubicacion']);
        $ubicacion = new UbicacionModel();
        $ubicacion->setUbicacion($this->data['ubicacion']);
        
        $this->response($ubicacion->save());
    }
}