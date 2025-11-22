<?php
class abmMenuRol {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idmenu']))
                $where.=" and idmenu =".$param['idmenu'];
            if  (isset($param['idrol']))
                $where.=" and idrol =".$param['idrol'];
        }
        $arreglo = MenuRol::listar($where);
        return $arreglo;
    }

    public function alta($param){
        
        $resp = false;
        if(isset($param['objMenu']) && isset($param['objRol'])){
            $obj = new MenuRol();
            $obj->setear($param['objMenu'], $param['objRol']);
            if ($obj->insertar()){ $resp = true; }
        }
        return $resp;
    }

    public function baja($param){
       
        $resp = false;
        if(isset($param['objMenu']) && isset($param['objRol'])){
            $obj = new MenuRol();
            $obj->setear($param['objMenu'], $param['objRol']);
            if ($obj->eliminar()){ $resp = true; }
        }
        return $resp;
    }
}
?>