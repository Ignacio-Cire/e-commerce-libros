<?php
class Compra {
    private $idcompra;
    private $cofecha;
    private $idusuario; 
    private $mensajeoperacion;

    public function __construct(){
        $this->idcompra="";
        $this->cofecha="";
        $this->idusuario="";
        $this->mensajeoperacion ="";
    }

    public function setear($idcompra, $cofecha, $idusuario){
        $this->idcompra = $idcompra;
        $this->cofecha = $cofecha;
        $this->idusuario = $idusuario;
    }

    public function getIdCompra(){ return $this->idcompra; }
    public function getCoFecha(){ return $this->cofecha; }
    public function getIdUsuario(){ return $this->idusuario; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM compra WHERE idcompra = ".$this->idcompra;
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2=$base->Registro()){
                    $this->setear($row2['idcompra'],$row2['cofecha'],$row2['idusuario']);
                    $resp= true;
                }
            }
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compra(cofecha, idusuario) VALUES('".$this->cofecha."',".$this->idusuario.");";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idcompra = $base->devuelveID();
                $resp = true;
            } else {
                $this->mensajeoperacion = "Compra->insertar: ".$base->getError();
            }
        } else {
            $this->mensajeoperacion = "Compra->insertar: ".$base->getError();
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compra SET cofecha='".$this->cofecha."', idusuario=".$this->idusuario." WHERE idcompra=".$this->idcompra;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            }
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compra ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new Compra();
                    $obj->setear($row2['idcompra'],$row2['cofecha'],$row2['idusuario']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>