<?php
class abmProducto {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idproducto']))
                $where.=" and idproducto =".$param['idproducto'];
             if  (isset($param['pronombre']))
                $where.=" and pronombre ='".$param['pronombre']."'";
        }
        $arreglo = Producto::listar($where);
        return $arreglo;
    }

    public function alta($param){
        $resp = false;
        $obj = new Producto();
        $precio = isset($param['proprecio']) ? $param['proprecio'] : 0;
        $imagen = isset($param['proimagen']) ? $param['proimagen'] : '';
        $obj->setear(null, $param['pronombre'], $param['prodetalle'], $param['procantstock'], $precio, $imagen);
        if ($obj->insertar()){ $resp = true; }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        if (isset($param['idproducto'])){
            $obj = new Producto();
            
            $obj->setear($param['idproducto'], null, null, null, null, null);
            $obj->cargar();
            
            $nombre = isset($param['pronombre']) ? $param['pronombre'] : $obj->getProNombre();
            $detalle = isset($param['prodetalle']) ? $param['prodetalle'] : $obj->getProDetalle();
            $stock = isset($param['procantstock']) ? $param['procantstock'] : $obj->getProCantStock();
            $precio = isset($param['proprecio']) ? $param['proprecio'] : $obj->getProPrecio();
            $imagen = isset($param['proimagen']) ? $param['proimagen'] : $obj->getProImagen();

            $obj->setear($param['idproducto'], $nombre, $detalle, $stock, $precio, $imagen);
            if($obj->modificar()){ $resp = true; }
        }
        return $resp;
    }

    public function modificarStock($idProducto, $cantidadRestar){
        $resp = false;
        $objProd = new Producto();
       
        $objProd->setear($idProducto, null, null, null, null, null);
        if($objProd->cargar()){
            $stockActual = $objProd->getProCantStock();
            $nuevoStock = $stockActual - $cantidadRestar;
            if($nuevoStock >= 0){
                $objProd->setProCantStock($nuevoStock);
                $resp = $objProd->modificar();
            }
        }
        return $resp;
    }
}
?>