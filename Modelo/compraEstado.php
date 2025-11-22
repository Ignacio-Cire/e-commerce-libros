<?php
class CompraEstado {
    private $idcompraestado;
    private $idcompra;
    private $idcompraestadotipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeoperacion;

    public function __construct(){
        $this->idcompraestado="";
        $this->idcompra="";
        $this->idcompraestadotipo="";
        $this->cefechaini=null;
        $this->cefechafin=null;
    }
    
    public function setear($idcompraestado, $idcompra, $idcompraestadotipo, $cefechaini, $cefechafin){
        $this->idcompraestado = $idcompraestado;
        $this->idcompra = $idcompra;
        $this->idcompraestadotipo = $idcompraestadotipo;
        $this->cefechaini = $cefechaini;
        $this->cefechafin = $cefechafin;
    }

    // Getters y Setters...
    public function getIdCompraEstado(){ return $this->idcompraestado; }
    public function getIdCompraEstadoTipo(){ return $this->idcompraestadotipo; }
    public function getCefechaFin(){ return $this->cefechafin; }
    public function setCefechaFin($fecha){ $this->cefechafin = $fecha; }
    
    public function cargar(){
         $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestado WHERE idcompraestado = ".$this->idcompraestado;
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2=$base->Registro()){
                    $this->setear($row2['idcompraestado'], $row2['idcompra'], $row2['idcompraestadotipo'], $row2['cefechaini'], $row2['cefechafin']);
                    $resp= true;
                }
            }
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        
        $sql = "INSERT INTO compraestado(idcompra, idcompraestadotipo, cefechaini, cefechafin) VALUES(".$this->idcompra.",".$this->idcompraestadotipo.",'".$this->cefechaini."', NULL);";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idcompraestado = $base->devuelveID();
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $fechaFinSQL = $this->cefechafin != null ? "'".$this->cefechafin."'" : "NULL";
        $sql="UPDATE compraestado SET cefechafin=$fechaFinSQL WHERE idcompraestado=".$this->idcompraestado;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) { $resp = true; }
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compraestado ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new CompraEstado();
                    $obj->setear($row2['idcompraestado'], $row2['idcompra'], $row2['idcompraestadotipo'], $row2['cefechaini'], $row2['cefechafin']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>