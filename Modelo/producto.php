<?php
class Producto {
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proprecio;  // Nuevo
    private $proimagen;  // Nuevo
    private $mensajeoperacion;

    public function __construct(){
        $this->idproducto="";
        $this->pronombre="";
        $this->prodetalle="";
        $this->procantstock="";
        $this->proprecio=0;
        $this->proimagen="";
        $this->mensajeoperacion ="";
    }

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock, $proprecio, $proimagen){
        $this->idproducto = $idproducto;
        $this->pronombre = $pronombre;
        $this->prodetalle = $prodetalle;
        $this->procantstock = $procantstock;
        $this->proprecio = $proprecio;
        $this->proimagen = $proimagen;
    }

    // Getters
    public function getIdProducto(){ return $this->idproducto; }
    public function getProNombre(){ return $this->pronombre; }
    public function getProDetalle(){ return $this->prodetalle; }
    public function getProCantStock(){ return $this->procantstock; }
    public function getProPrecio(){ return $this->proprecio; }
    public function getProImagen(){ return $this->proimagen; }
    
    public function setProCantStock($cant){ $this->procantstock = $cant; }
    public function setMensajeoperacion($mensaje){ $this->mensajeoperacion = $mensaje; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM producto WHERE idproducto = ".$this->idproducto;
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2=$base->Registro()){
                    
                    $precio = isset($row2['proprecio']) ? $row2['proprecio'] : 0;
                    $img = isset($row2['proimagen']) ? $row2['proimagen'] : '';
                    
                    $this->setear($row2['idproducto'], $row2['pronombre'], $row2['prodetalle'], $row2['procantstock'], $precio, $img);
                    $resp= true;
                }
            } else {
                $this->setMensajeoperacion("Producto->cargar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Producto->cargar: ".$base->getError());
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        
        $sql = "INSERT INTO producto(pronombre, prodetalle, procantstock, proprecio, proimagen) VALUES('".$this->pronombre."','".$this->prodetalle."',".$this->procantstock.",".$this->proprecio.",'".$this->proimagen."');";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idproducto = $base->devuelveID();
                $resp = true;
            } else {
                $this->setMensajeoperacion("Producto->insertar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Producto->insertar: ".$base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql="UPDATE producto SET pronombre='".$this->pronombre."', prodetalle='".$this->prodetalle."', procantstock=".$this->procantstock.", proprecio=".$this->proprecio.", proimagen='".$this->proimagen."' WHERE idproducto=".$this->idproducto;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Producto->modificar: ".$base->getError());
            }
        } else {
            $this->setMensajeoperacion("Producto->modificar: ".$base->getError());
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM producto ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new Producto();
                    $precio = isset($row2['proprecio']) ? $row2['proprecio'] : 0;
                    $img = isset($row2['proimagen']) ? $row2['proimagen'] : '';
                    $obj->setear($row2['idproducto'],$row2['pronombre'],$row2['prodetalle'],$row2['procantstock'], $precio, $img);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>