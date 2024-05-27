<?php

namespace ApiEntregas\Libs;

use Exception;
use ApiEntregas\Libs\Controller;
use ApiEntregas\Models\UsuarioModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends Controller
{
    private $userId;
    private $token;
    private $key;

    public function __construct()
    {
        parent::__construct();
        $this->key = '12345';
        $this->validateToken();
    }

    public function validateToken()
    {
        if($_GET['url'] != 'login'){
            $headers = apache_request_headers();
            if(!isset($headers['Authorization'])){
                $this->response(["ok"=>false, "msj"=>"Token requerido"]);
            }
    
            $token = str_replace("Bearer ", "", $headers['Authorization']);
            $this->verifyToken($token);
        }
    }

    public function verifyToken($token)
    {
        try{
            $decode = JWT::decode($token, new Key($this->key, 'HS256'));
            $auth = new UsuarioModel();

            if($auth->existsToken($token)){
                $this->userId = $decode->data->id;
                return true;
            }

            $this->response(["ok"=>false, "msj"=>"Token no existe"]);
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
            'exp' => $time + 28800,
            'data' => ['id' => $user['idUsuario'], 'usuario' => $user['usuario']]
        ];

        return JWT::encode($token, $this->key, 'HS256');
    }
}