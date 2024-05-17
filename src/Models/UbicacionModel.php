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
}