<?php

namespace ApiEntregas\Models;

use ApiEntregas\Libs\Model;
use PDO;
use PDOException;

class TransporteModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public static function getTransportes()
    {
        try{
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM transporte");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log('TransporteModel::getTransportes -> ' . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }

    public function save()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();

            $query = $this->prepare("INSERT INTO transporte (transporte) VALUES (?)");
            $query->bindValue(1, $this->transporte, PDO::PARAM_STR);

            if($query->execute()){
                return array("ok" => true, "msj" => "Transporte registrado");
            }
        } catch(PDOException $e){
            error_log('TransporteModel::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function edit()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();

            $query = $this->prepare("UPDATE transporte SET transporte = :transporte WHERE idTransporte = :idTransporte");
            $query->bindValue(':idTransporte', $this->idTransporte, PDO::PARAM_INT);
            $query->bindValue(':transporte', $this->transporte, PDO::PARAM_STR);

            if($query->execute()){
                return array("ok" => true, "msj" => "Transporte editado");
            }

        } catch(PDOException $e){
            error_log('TransporteModel::edit()->' .$e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function getTransporte()
    {
        return $this->transporte;
    }

    public function setTransporte($transporte)
    {
        $this->transporte = $transporte;
    }

    public function getId()
    {
        return $this->idTransporte;
    }

    public function setId($idTransporte)
    {
        $this->idTransporte = $idTransporte;
    }
}