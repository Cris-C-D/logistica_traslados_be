<?php

namespace ApiEntregas\Models;

use ApiEntregas\Libs\Model;
use PDO;
use PDOException;

class UbicacionModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getUbicaciones()
    {
        try{
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM ubicacion");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log('UbicacionModel::getUbicaciones -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function save()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();

            $query = $this->prepare("INSERT INTO ubicacion (ubicacion) VALUES (?)");
            $query->bindValue(1, $this->ubicacion, PDO::PARAM_STR);

            if($query->execute()){
                return array("ok" => true, "msj" => "Ubicacion registrada");
            }
        } catch(PDOException $e){
            error_log('UbicacionModel::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function edit()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();
            
            $query = $this->prepare("UPDATE ubicacion SET ubicacion = :ubicacion WHERE idUbicacion = :idUbicacion");
            $query->bindValue(':idUbicacion', $this->idUbicacion, PDO::PARAM_INT);
            $query->bindValue(':ubicacion', $this->ubicacion, PDO::PARAM_STR);
            
            if($query->execute()){
                return array("ok" => true, "msj" => "Ubicacion editada");
            }
        } catch(PDOException $e){
            error_log('UbicacionModel::edit()->' .$e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }

    public function getId()
    {
        return $this->idUbicacion;
    }

    public function setId($idUbicacion)
    {
        $this->idUbicacion = $idUbicacion;
    }
}