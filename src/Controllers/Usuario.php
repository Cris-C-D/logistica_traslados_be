<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\UsuarioModel;

class Usuario extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getUsuarios()
    {
        $this->response(["usuarios" => UsuarioModel::getUsuarios()]);
    }

    public function create()
    {
        $this->exists(['nombre', 'apellidos', 'idTipoUsuario', 'usuario', 'password']);
        $usuario = new UsuarioModel();
        $usuario->setNombre($this->data['nombre']);
        $usuario->setApellidos($this->data['apellidos']);
        $usuario->setIdTipoUsuario($this->data['idTipoUsuario']);
        $usuario->setUsuario($this->data['usuario']);
        $usuario->setPassword($this->data['password']);
        
        $this->response($usuario->save());
    }
}