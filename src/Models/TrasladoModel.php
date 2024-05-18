<?php

namespace ApiEntregas\Models;

use ApiEntregas\Libs\Model;
use PDO;
use PDOException;

class TrasladoModel extends Model
{
    public $idUsuario;
    public $idUbicacionOrigen;
    public $idUbicacionDestino;
    public $idTransporte;
    public $idTipoTraslado;
    public $fechaInicio;
    public $fechaFin;   

    public function __construct()
    {
        parent::__construct();
    }

    public static function getTiposTraslado()
    {
        try{
            $pdo = new Model();
            $query = $pdo->query("SELECT * FROM tipo_traslado");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e){
            error_log('TrasladoModel::getTipoTraslado -> ' . $e->getMessage());
            return array("msj" => $e->getMessage());
        }
    }

    public static function get(int $idUsuario = 0, int $idOrigen = 0, int $idDestino = 0, int $idTransporte = 0)
    {
        try{
            $pdo = new Model();
            $arrayFilters = array();
            $arrayParams = array();

            if($idUsuario!=0){
                $arrayFilters[]="idUsuario LIKE :id_usuario";
                $arrayParams[':id_usuario'] = "%$idUsuario%";
            }

            if($idOrigen!=0){
                $arrayFilters[]="idOrigen LIKE :id_origen";
                $arrayParams[':id_origen'] = "%$idOrigen%";
            }

            if($idDestino!=0){
                $arrayFilters[]="idDestino LIKE :id_destino";
                $arrayParams[':id_destino'] = "%$idDestino%";
            }

            if($idTransporte!=0){
                $arrayFilters[]="idTransporte LIKE :id_transporte";
                $arrayParams[':id_transporte'] = "%$idTransporte%";
            }

            $sqlFiltros = "";
            if ($arrayFilters != "") {
                for ($i = 0; $i < count($arrayFilters); $i++) {
                    if ($i == 0) {
                        $sqlFiltros .= " WHERE " . $arrayFilters[$i];
                    } else {
                        $sqlFiltros .= " AND " . $arrayFilters[$i];
                    }
                }
                echo $sqlFiltros;
            }



            $sql = "WITH difference_in_seconds AS (
                SELECT t.idTraslado, p.idUsuario, CONCAT(p.nombre, ' ', p.apellidos) AS nombre, uo.idUbicacion AS idOrigen, uo.ubicacion AS origen, ud.idUbicacion AS idDestino,  ud.ubicacion AS destino, tr.idTransporte, tr.transporte, tt.idTipo, tt.tipoTraslado, t.fechaInicio, t.fechaFin, TIMESTAMPDIFF(SECOND, t.fechaInicio, t.fechaFin) AS seconds
                FROM traslado t 
                INNER JOIN usuario p ON t.idUsuario=p.idUsuario
                INNER JOIN ubicacion uo ON t.idUbicacionOrigen=uo.idUbicacion
                INNER JOIN ubicacion ud ON t.idUbicacionDestino=ud.idUbicacion
                INNER JOIN transporte tr ON t.idTransporte=tr.idTransporte
                INNER JOIN tipo_traslado tt ON t.idTipoTraslado=tt.idTipo
                ),
                differences AS (
                SELECT idTraslado, idUsuario, nombre, idOrigen, origen, idDestino, destino, idTransporte, transporte, idTipo, tipoTraslado, fechaInicio, fechaFin, seconds, MOD(seconds, 60) AS seconds_part, MOD(seconds, 3600) AS minutes_part, MOD(seconds, 3600 * 24) AS hours_part
                FROM difference_in_seconds
                )
                SELECT idTraslado, idUsuario, nombre, idOrigen, origen, idDestino, destino, idTransporte, transporte, idTipo tipoTraslado, fechaInicio, fechaFin, CONCAT(FLOOR(seconds / 3600 / 24), ' dias ', FLOOR(hours_part / 3600), ' horas ', FLOOR(minutes_part / 60), ' minutos '
                ) AS tiempoTraslado
                FROM differences";

            $sql .=$sqlFiltros;
            $query = $pdo->prepare($sql);

            $query->execute($arrayParams);

            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            error_log('TrasladoModel::get ->' . $e->getMessage());
            return array("msj" => $e->getMessage());
        }
    }

    public function save()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();

            $query = $this->prepare("INSERT INTO traslado (idUsuario, idUbicacionOrigen, idUbicacionDestino, idTransporte, idTipoTraslado, fechaInicio, fechaFin) VALUES (?,?,?,?,?,?,?) ");
            $query->bindValue(1, $this->idUsuario, PDO::PARAM_INT);
            $query->bindValue(2, $this->idUbicacionOrigen, PDO::PARAM_INT);
            $query->bindValue(3, $this->idUbicacionDestino, PDO::PARAM_INT);
            $query->bindValue(4, $this->idTransporte, PDO::PARAM_INT);
            $query->bindValue(5, $this->idTipoTraslado, PDO::PARAM_INT);
            $query->bindValue(6, $this->fechaInicio, PDO::PARAM_STR);
            $query->bindValue(7, $this->fechaFin, PDO::PARAM_STR);
            
            if($query->execute()){
                return array("ok" => true, "msj" => "Traslado registrado");
            }
        } catch (PDOException $e){
            error_log('TrasladoModel::save()->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;
    }

    public function getIdUbicacionOrigen()
    {
        return $this->idUbicacionOrigen;
    }

    public function setIdUbicacionOrigen($idUbicacionOrigen)
    {
        $this->idUbicacionOrigen = $idUbicacionOrigen;
    }

    public function getIdUbicacionDestino()
    {
        return $this->idUbicacionDestino;
    }

    public function setIdUbicacionDestino($idUbicacionDestino)
    {
        $this->idUbicacionDestino = $idUbicacionDestino;
    }

    public function getIdTransporte()
    {
        return $this->idTransporte;
    }

    public function setIdTransporte($idTransporte)
    {
        $this->idTransporte = $idTransporte;
    }

    public function getIdTipoTraslado()
    {
        return $this->idTipoTraslado;
    }

    public function setIdTipoTraslado($idTipoTraslado)
    {
        $this->idTipoTraslado = $idTipoTraslado;
    }

    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }
}