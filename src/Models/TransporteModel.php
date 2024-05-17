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
}