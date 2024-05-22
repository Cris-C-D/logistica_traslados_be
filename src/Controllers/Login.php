<?php

namespace ApiEntregas\Controllers;

use ApiEntregas\Libs\Auth;
use ApiEntregas\Models\UsuarioModel;

class Login extends Auth
{
    public function __construct()
    {
        parent::__construct();
    }

    public function auth()
    {
        $this->exists(['usuario', 'password']);

        $user = UsuarioModel::login($this->data['usuario'], $this->data['password']);
        if($user!=null){
            $this->initialize($user);
        }

        $this->response(["ok" => false, "msj" => "Usuario o contraseña incorrectos"]);
    }
}