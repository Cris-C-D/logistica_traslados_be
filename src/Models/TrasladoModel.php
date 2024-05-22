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

    public static function get(int $idUsuario = 0, int $idUbicacionOrigen = 0, int $idUbicacionDestino = 0, int $idTransporte = 0, int $open = 0, string $fechaInicio = '', string $fechaFin = '')
    {
        try{
            $pdo = new Model();
            $arrayFilters = array();
            $arrayParams = array();

            if($idUsuario!=0){
                $arrayFilters[]="idUsuario LIKE :id_usuario";
                $arrayParams[':id_usuario'] = $idUsuario;
            }

            if($idUbicacionOrigen!=0){
                $arrayFilters[]="idUbicacionOrigen LIKE :id_origen";
                $arrayParams[':id_origen'] = $idUbicacionOrigen;
            }

            if($idUbicacionDestino!=0){
                $arrayFilters[]="idUbicacionDestino LIKE :id_destino";
                $arrayParams[':id_destino'] = $idUbicacionDestino;
            }

            if($idTransporte!=0){
                $arrayFilters[]="idTransporte LIKE :id_transporte";
                $arrayParams[':id_transporte'] = $idTransporte;
            }

            if($open!=0){
                $arrayFilters[]="fechaFin IS NULL";
            }

            if($fechaInicio!=''){
                if($fechaFin!=''){
                    $arrayFilters[]="fechaInicio BETWEEN :fecha_inicio AND :fecha_fin";
                    $arrayParams[':fecha_inicio'] = "$fechaInicio";
                    $arrayParams[':fecha_fin'] = "$fechaFin";
                } else{
                    $arrayFilters[]="fechaInicio LIKE :fecha_inicio";
                    $arrayParams[':fecha_inicio'] = "%$fechaInicio%";
                }
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
            }



            $sql = "WITH difference_in_seconds AS (
                SELECT t.idTraslado, p.idUsuario, CONCAT(p.nombre, ' ', p.apellidos) AS nombre, uo.idUbicacion AS idUbicacionOrigen, uo.ubicacion AS origen, ud.idUbicacion AS idUbicacionDestino,  ud.ubicacion AS destino, tr.idTransporte, tr.transporte, tt.idTipo, tt.tipoTraslado AS idTipoTraslado, t.fechaInicio, t.fechaFin, TIMESTAMPDIFF(SECOND, t.fechaInicio, t.fechaFin) AS seconds, t.comentario
                FROM traslado t 
                INNER JOIN usuario p ON t.idUsuario=p.idUsuario
                INNER JOIN ubicacion uo ON t.idUbicacionOrigen=uo.idUbicacion
                INNER JOIN ubicacion ud ON t.idUbicacionDestino=ud.idUbicacion
                INNER JOIN transporte tr ON t.idTransporte=tr.idTransporte
                INNER JOIN tipo_traslado tt ON t.idTipoTraslado=tt.idTipo
                ),
                differences AS (
                SELECT idTraslado, idUsuario, nombre, idUbicacionOrigen, origen, idUbicacionDestino, destino, idTransporte, transporte, idTipo, idTipoTraslado, fechaInicio, fechaFin, seconds, MOD(seconds, 60) AS seconds_part, MOD(seconds, 3600) AS minutes_part, MOD(seconds, 3600 * 24) AS hours_part, comentario
                FROM difference_in_seconds
                )
                SELECT idTraslado, idUsuario, nombre, idUbicacionOrigen, origen, idUbicacionDestino, destino, idTransporte, transporte, idTipo idTipoTraslado, fechaInicio, fechaFin, CONCAT(FLOOR(seconds / 3600 / 24), ' dias ', FLOOR(hours_part / 3600), ' horas ', FLOOR(minutes_part / 60), ' minutos '
                ) AS tiempoTraslado, comentario
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

    public function close()
    {
        try{
            $c = $this->connect();
            $c->beginTransaction();
    
            $query = $this->prepare("UPDATE traslado SET fechaFin = :fechaFin, comentario = :comentario WHERE idTraslado = :idTraslado");
            $query->bindValue(':idTraslado', $this->idTraslado, PDO::PARAM_INT);
            $query->bindValue(':fechaFin', $this->fechaFin, PDO::PARAM_STR);
            $query->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);

            if($query->execute()){
                return array("ok" => true, "msj" => "Traslado terminado");
            }
        } catch(PDOException $e){
            error_log('TrasladoModel::close->' . $e->getMessage());
            return array("ok" => false, "msj" => $e->getMessage());
        }

    }

    public function getId()
    {
        return $this->idTraslado;
    }

    public function setId($idTraslado)
    {
        $this->idTraslado = $idTraslado;
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

    public function getComentario()
    {
        return $this->comentario;
    }

    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }
}