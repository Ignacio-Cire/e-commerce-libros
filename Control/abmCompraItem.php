<?php
class abmCompraItem {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompra']))
                $where.=" and idcompra =".$param['idcompra'];
            if  (isset($param['idproducto']))
                $where.=" and idproducto =".$param['idproducto'];
             if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem =".$param['idcompraitem'];
        }
        $arreglo = CompraItem::listar($where);
        return $arreglo;
    }

    public function alta($param){
        $resp = false;
        $obj = new CompraItem();
        
        $obj->setear(null, $param['objProducto'], $param['objCompra'], $param['cicantidad']);
        if ($obj->insertar()){ $resp = true; }
        return $resp;
    }
    
    public function baja($param){
        $resp = false;
        if(isset($param['idcompraitem'])){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], null, null, null);
            if($obj->eliminar()){ $resp = true; }
        }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        if(isset($param['idcompraitem'])){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], null, null, $param['cicantidad']);
            if($obj->modificar()){ $resp = true; }
        }
        return $resp;
    }
}
?>