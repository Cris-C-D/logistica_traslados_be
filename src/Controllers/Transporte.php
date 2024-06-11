<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Auth;
use ApiEntregas\Models\TransporteModel;

class Transporte extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getTransportes()
    {
        $this->response(["transportes" => TransporteModel::getTransportes()]);
    }

    public function create()
    {
        $this->exists(['transporte']);
        $transporte = new TransporteModel();
        $transporte->setTransporte($this->data['transporte']);

        $this->response($transporte->save());
    }

    public function edit()
    {
        $this->exists(['idTransporte', 'transporte']);
        $transporte = new TransporteModel();
        $transporte->setId($this->data['idTransporte']);
        $transporte->setTransporte($this->data['transporte']);

        $this->response($transporte->edit());
    }
}