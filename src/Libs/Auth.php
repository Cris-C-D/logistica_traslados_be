<?php

namespace ApiEntregas\Libs;

use Exception;
use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\UsuarioModel;
use Firebase\JWT\JWT;

class Auth extends Controller
{
    private $userId;
    private $token;
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->key = '12345';
    }

    public function verifyToken($token)
    {
        try{
            $decode = JWT::decode($token, new Key($this->key, 'HS256'));
            $auth = new UsuarioModel();
        } catch (Exception $e){
            $this->response(["ok"=>false, "msj"=>"Token invalido"]);
        }
    }

    public function initialize(array $user)
    {
        $this->token = $this->generateToken($user);
        $auth = new UsuarioModel();
        $auth->setId($user['idUsuario']);
        $auth->setToken($this->token);
        $auth->updateToken();
        $user['token'] = $this->token;

        $this->response(["data" => $user]);
    }

    public function generateToken(array $user)
    {
        $time = time();
        $token = [
            'iat' => $time,
            'exp' => $time * 60 * 60,
            'data' => ['id' => $user['idUsuario'], 'usuario' => $user['usuario']]
        ];

        return JWT::encode($token, $this->key, 'HS256');
    }
}