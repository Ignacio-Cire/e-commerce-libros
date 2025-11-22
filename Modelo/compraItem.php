<?php
class CompraItem {
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $mensajeoperacion;

    public function __construct(){
        $this->idcompraitem = "";
        $this->objProducto = null;
        $this->objCompra = null;
        $this->cicantidad = "";
    }

    public function setear($idcompraitem, $objProducto, $objCompra, $cicantidad){
        $this->idcompraitem = $idcompraitem;
        $this->objProducto = $objProducto;
        $this->objCompra = $objCompra;
        $this->cicantidad = $cicantidad;
    }

    public function getIdCompraItem(){ return $this->idcompraitem; }
    public function getObjProducto(){ return $this->objProducto; }
    public function getObjCompra(){ return $this->objCompra; }
    public function getCiCantidad(){ return $this->cicantidad; }
    public function setCiCantidad($cant){ $this->cicantidad = $cant; }
    public function getMensajeoperacion(){ return $this->mensajeoperacion; }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = ".$this->idcompraitem;
        if ($base->Iniciar()) {
            if($base->Ejecutar($sql)){
                if($row2 = $base->Registro()){
                    $objProd = new Producto();
                    
                    $objProd->setear($row2['idproducto'], null, null, null, null, null);
                    $objProd->cargar();
                    
                    $objComp = new Compra();
                    $objComp->setear($row2['idcompra'], null, null);
                    $objComp->cargar();

                    $this->setear($row2['idcompraitem'], $objProd, $objComp, $row2['cicantidad']);
                    $resp = true;
                }
            }
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraitem(idproducto, idcompra, cicantidad) VALUES(".$this->objProducto->getIdProducto().",".$this->objCompra->getIdCompra().",".$this->cicantidad.");";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $this->idcompraitem = $base->devuelveID();
                $resp = true;
            } else {
                $this->mensajeoperacion = "CompraItem->insertar: ".$base->getError();
            }
        } else {
            $this->mensajeoperacion = "CompraItem->insertar: ".$base->getError();
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraitem SET cicantidad=".$this->cicantidad." WHERE idcompraitem=".$this->idcompraitem;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->mensajeoperacion = "CompraItem->modificar: ".$base->getError();
            }
        } else {
            $this->mensajeoperacion = "CompraItem->modificar: ".$base->getError();
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraitem WHERE idcompraitem=".$this->idcompraitem;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->mensajeoperacion = "CompraItem->eliminar: ".$base->getError();
            }
        } else {
            $this->mensajeoperacion = "CompraItem->eliminar: ".$base->getError();
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem ";
        if ($parametro!="") { $sql.='WHERE '.$parametro; }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2 = $base->Registro()) {
                    $obj = new CompraItem();
                    $objProd = new Producto();
                    
                    $objProd->setear($row2['idproducto'], null, null, null, null, null);
                    $objProd->cargar();
                    
                    $objComp = new Compra(); 
                    $objComp->setear($row2['idcompra'], null, null);
                    
                    $obj->setear($row2['idcompraitem'], $objProd, $objComp, $row2['cicantidad']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
?>