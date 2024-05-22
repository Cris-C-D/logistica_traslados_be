<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Auth;
use ApiEntregas\Models\UsuarioModel;

class Usuario extends Auth
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

    public function editToken()
    {
        $this->exists(['idUsuario', 'token']);

        $usuario = new UsuarioModel();
        $usuario->setId($this->data['idUsuario']);
        $usuario->setToken($this->data['token']);

        $this->response($usuario->updateToken());
        
    }
}