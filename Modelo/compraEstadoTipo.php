<?php
class CompraEstadoTipo {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeoperacion;

    public function __construct(){
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
        $this->mensajeoperacion = "";
    }

    public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle){
        $this->idcompraestadotipo = $idcompraestadotipo;
        $this->cetdescripcion = $cetdescripcion;
        $this->cetdetalle = $cetdetalle;
    }

    public function getIdCompraEstadoTipo(){ return $this->idcompraestadotipo; }
    public function getCetDescripcion(){ return $this->cetdescripcion; }
    public function getCetDetalle(){ return $this->cetdetalle; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = ".$this->idcompraestadotipo;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row2 = $base->Registro()) {
                    $this->setear($row2['idcompraestadotipo'], $row2['cetdescripcion'], $row2['cetdetalle']);
                    $resp = true;
                }
            } else {
                $this->mensajeoperacion = "CompraEstadoTipo->cargar: ".$base->getError();
            }
        } else {
            $this->mensajeoperacion = "CompraEstadoTipo->cargar: ".$base->getError();
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2 = $base->Registro()) {
                    $obj = new CompraEstadoTipo();
                    $obj->setear($row2['idcompraestadotipo'], $row2['cetdescripcion'], $row2['cetdetalle']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>