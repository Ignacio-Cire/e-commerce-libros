<?php
class UsuarioRol {
    private $objUsuario;
    private $objRol;
    private $mensajeoperacion;

    public function __construct(){
        $this->objUsuario = null;
        $this->objRol = null;
    }
    public function setear($objUsuario, $objRol){
        $this->objUsuario = $objUsuario;
        $this->objRol = $objRol;
    }
    public function getObjUsuario(){ return $this->objUsuario; }
    public function getObjRol(){ return $this->objRol; }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuariorol(idusuario, idrol) VALUES(".$this->objUsuario->getIdUsuario().",".$this->objRol->getIdRol().");";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) { $resp = true; }
        }
        return $resp;
    }
    
    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM usuariorol ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new UsuarioRol();
                    $user = new Usuario(); $user->setear($row2['idusuario'], null, null, null, null); $user->cargar();
                    $rol = new Rol(); $rol->setear($row2['idrol'], null); $rol->cargar();
                    $obj->setear($user, $rol);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>