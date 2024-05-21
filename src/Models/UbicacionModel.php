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

    public function getUbicacion()
    {
        return $this->ubicacion;
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;
    }
}