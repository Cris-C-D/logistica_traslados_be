<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\UbicacionModel;

class Ubicacion extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUbicaciones()
    {
        $this->response(["ubicaciones" => UbicacionModel::getUbicaciones()]);
    }
}