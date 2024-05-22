<?php

namespace ApiEntregas\Models;

use ApiEntregas\Libs\Model;
use PDO;
use PDOException;

class UsuarioModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public static function getUsuarios()
    {
        try{
            $pdo = new Model();
            $query = $pdo->query("SELECT u.idUsuario, u.nombre, u.apellidos, u.idTipoUsuario, t.tipoUsuario FROM usuario u INNER JOIN tipo_usuario t");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log("UsuarioModel::getUsuarios ->" . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function save()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();
            
            $query = $this->prepare("INSERT INTO usuario (nombre, apellidos, idTipoUsuario, usuario, password) VALUES (?,?,?,?,?)");
            $query->bindValue(1, $this->nombre, PDO::PARAM_STR);
            $query->bindValue(2, $this->apellidos, PDO::PARAM_STR);
            $query->bindValue(3, $this->idTipoUsuario, PDO::PARAM_INT);
            $query->bindValue(4, $this->usuario, PDO::PARAM_STR);
            $query->bindValue(5, $this->password, PDO::PARAM_STR);

            if($query->execute()){
                return array("ok" => true, "msj" => "Usuario registrasdo");
            }
        } catch(PDOException $e){
            error_log('UsuarioModel::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function updateToken()
    {
        try{
            $query = $this->prepare("UPDATE usuario SET token = :token WHERE idUsuario = :idUsuario;");
            return $query->execute(['idUsuario' => $this->idUsuario, 'token' => $this->token]);
        } catch(PDOException $e) {
            error_log("UsuarioModel::updateToken ->" . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function existsToken($token)
    {
        try{

        } catch(PDOException $e){
            error_log("UsuarioModel::existsToken ->" . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public static function login($usuario, $password)
    {
        try{
            $pdo = new Model();
            $query = $pdo->prepare("SELECT * FROM usuario u WHERE u.usuario = :usuario");
            $query->execute(['usuario'=>$usuario]);

            if($query->rowCount()==1){
                $user = $query->fetch(PDO::FETCH_ASSOC);
                if($password == $user['password']){
                    return $user;
                }
                return null;
            }
            return null;
        } catch(PDOException $e){
            error_log("UsuarioModel::login ->" . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function getId()
    {
        return $this->idUsuario;
    }

    public function setId($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function getIdTipoUsuario()
    {
        return $this->idTipoUsuario;
    }

    public function setIdTipoUsuario($idTipoUsuario)
    {
        $this->idTipoUsuario = $idTipoUsuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
}