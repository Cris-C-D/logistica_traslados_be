<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\TransporteModel;

class Transporte extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTransportes()
    {
        $this->response(["transportes" => TransporteModel::getTransportes()]);
    }
}