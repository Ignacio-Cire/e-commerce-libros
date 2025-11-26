<?php
class Usuario {
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $mensajeoperacion;

    public function __construct(){
        $this->idusuario = "";
        $this->usnombre = "";
        $this->uspass = "";
        $this->usmail = "";
        $this->usdeshabilitado = null;
        $this->mensajeoperacion = "";
    }

    public function setear($idusuario, $usnombre, $uspass, $usmail, $usdeshabilitado){
        $this->idusuario = $idusuario;
        $this->usnombre = $usnombre;
        $this->uspass = $uspass;
        $this->usmail = $usmail;
        $this->usdeshabilitado = $usdeshabilitado;
    }

    public function getIdUsuario(){ return $this->idusuario; }
    public function getUsNombre(){ return $this->usnombre; }
    public function getUsPass(){ return $this->uspass; }
    public function getUsMail(){ return $this->usmail; }
    public function getUsDeshabilitado(){ return $this->usdeshabilitado; }

    public function setUsNombre($valor){ $this->usnombre = $valor; }
    public function setUsPass($valor){ $this->uspass = $valor; }
    public function setUsMail($valor){ $this->usmail = $valor; }
    public function setUsDeshabilitado($valor){ $this->usdeshabilitado = $valor; }
    public function setMensajeoperacion($mensajeoperacion){ $this->mensajeoperacion = $mensajeoperacion; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE idusuario = ".$this->idusuario;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                if ($row2 = $base->Registro()) {
                    $this->setear($row2['idusuario'], $row2['usnombre'], $row2['uspass'], $row2['usmail'], $row2['usdeshabilitado']);
                    $resp = true;
                }
            } else { $this->setMensajeoperacion("Usuario->listar: ".$base->getError()); }
        } else { $this->setMensajeoperacion("Usuario->listar: ".$base->getError()); }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuario(usnombre, uspass, usmail)  VALUES('".$this->usnombre."','".$this->uspass."','".$this->usmail."');";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idusuario = $base->devuelveID();
                $resp = true;
            } else { $this->setMensajeoperacion("Usuario->insertar: ".$base->getError()); }
        } else { $this->setMensajeoperacion("Usuario->insertar: ".$base->getError()); }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuario SET usnombre='".$this->usnombre."', uspass='".$this->uspass."', usmail='".$this->usmail."' WHERE idusuario=".$this->idusuario;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else { $this->setMensajeoperacion("Usuario->modificar: ".$base->getError()); }
        } else { $this->setMensajeoperacion("Usuario->modificar: ".$base->getError()); }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE idusuario=".$this->idusuario;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else { $this->setMensajeoperacion("Usuario->eliminar: ".$base->getError()); }
        } else { $this->setMensajeoperacion("Usuario->eliminar: ".$base->getError()); }
        return $resp;
    }

    
    public static function listar($parametro=""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2 = $base->Registro()) {
                    $obj = new Usuario();
                    $obj->setear($row2['idusuario'], $row2['usnombre'], $row2['uspass'], $row2['usmail'], $row2['usdeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>