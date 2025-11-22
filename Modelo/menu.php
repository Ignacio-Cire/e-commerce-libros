<?php
class Menu {
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $idpadre;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function __construct(){
        $this->idmenu="";
        $this->menombre="";
        $this->medescripcion="";
        $this->idpadre=null;
        $this->medeshabilitado=null;
        $this->mensajeoperacion="";
    }

    public function setear($idmenu, $menombre, $medescripcion, $idpadre, $medeshabilitado){
        $this->idmenu = $idmenu;
        $this->menombre = $menombre;
        $this->medescripcion = $medescripcion;
        $this->idpadre = $idpadre;
        $this->medeshabilitado = $medeshabilitado;
    }
    
    
    public function getIdMenu(){ return $this->idmenu; }
    public function getMeNombre(){ return $this->menombre; }
    public function getMeDescripcion(){ return $this->medescripcion; }
    public function getIdPadre(){ return $this->idpadre; }
    public function getMeDeshabilitado(){ return $this->medeshabilitado; }
    public function setMeDeshabilitado($fecha){ $this->medeshabilitado = $fecha; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }
    public function setMensajeoperacion($mensaje){ $this->mensajeoperacion = $mensaje; }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menu WHERE idmenu = ".$this->idmenu;
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2=$base->Registro()){
                    $this->setear($row2['idmenu'],$row2['menombre'],$row2['medescripcion'],$row2['idpadre'],$row2['medeshabilitado']);
                    $resp= true;
                }
            }
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
       
        $padre = $this->idpadre != null ? $this->idpadre : "NULL";
        $sql = "INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado) VALUES('".$this->menombre."','".$this->medescripcion."',".$padre.", NULL);";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idmenu = $base->devuelveID();
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->insertar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $padre = $this->idpadre != null ? $this->idpadre : "NULL";
        $deshabilitado = $this->medeshabilitado != null ? "'".$this->medeshabilitado."'" : "NULL";
        
        $sql = "UPDATE menu SET menombre='".$this->menombre."', medescripcion='".$this->medescripcion."', idpadre=".$padre.", medeshabilitado=".$deshabilitado." WHERE idmenu=".$this->idmenu;
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->modificar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->modificar: ".$base->getError());
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menu ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new Menu();
                    $obj->setear($row2['idmenu'],$row2['menombre'],$row2['medescripcion'],$row2['idpadre'],$row2['medeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>