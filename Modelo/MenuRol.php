<?php
class MenuRol {
    private $objMenu;
    private $objRol;
    private $mensajeoperacion;

    public function __construct(){
        $this->objMenu = null;
        $this->objRol = null;
        $this->mensajeoperacion = "";
    }
    
    public function setear($objMenu, $objRol){
        $this->objMenu = $objMenu;
        $this->objRol = $objRol;
    }
    
    public function getObjMenu(){ return $this->objMenu; }
    public function getObjRol(){ return $this->objRol; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol(idmenu, idrol) VALUES(".$this->objMenu->getIdMenu().",".$this->objRol->getIdRol().");";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) { $resp = true; }
            else { $this->mensajeoperacion = $base->getError(); }
        } else { $this->mensajeoperacion = $base->getError(); }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenu=".$this->objMenu->getIdMenu()." AND idrol=".$this->objRol->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) { $resp = true; }
            else { $this->mensajeoperacion = $base->getError(); }
        } else { $this->mensajeoperacion = $base->getError(); }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menurol ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new MenuRol();
                    // Cargar objeto Menu
                    $menu = new Menu(); 
                    $menu->setear($row2['idmenu'],null,null,null,null); 
                    
                    // Cargar objeto Rol
                    $rol = new Rol(); 
                    $rol->setear($row2['idrol'],null); 
                    $rol->cargar();
                    
                    $obj->setear($menu, $rol);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>