<?php
class abmMenu {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idmenu']))
                $where.=" and idmenu =".$param['idmenu'];
            if  (isset($param['menombre']))
                $where.=" and menombre ='".$param['menombre']."'";
            if  (isset($param['medeshabilitado']))
                $where.=" and medeshabilitado IS NULL";
        }
        $arreglo = Menu::listar($where);
        return $arreglo;
    }

    public function alta($param){
        $resp = false;
        $obj = new Menu();
        $padre = isset($param['idpadre']) && $param['idpadre'] != "" ? $param['idpadre'] : null;
        $obj->setear(null, $param['menombre'], $param['medescripcion'], $padre, null);
        if ($obj->insertar()){ $resp = true; }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        if (isset($param['idmenu'])){
            $obj = new Menu();
            $obj->setear($param['idmenu'], null, null, null, null);
            if($obj->cargar()){
                $padre = isset($param['idpadre']) && $param['idpadre'] != "" ? $param['idpadre'] : $obj->getIdPadre();
                $nombre = isset($param['menombre']) ? $param['menombre'] : $obj->getMeNombre();
                $desc = isset($param['medescripcion']) ? $param['medescripcion'] : $obj->getMeDescripcion();
                $deshab = $obj->getMeDeshabilitado();
                
                $obj->setear($param['idmenu'], $nombre, $desc, $padre, $deshab);
                if($obj->modificar()){ $resp = true; }
            }
        }
        return $resp;
    }

    public function baja($param){
       
        $resp = false;
        if (isset($param['idmenu'])){
            $obj = new Menu();
            $obj->setear($param['idmenu'], null, null, null, null);
            if($obj->cargar()){
                $obj->setMeDeshabilitado(date('Y-m-d H:i:s'));
                if($obj->modificar()){ $resp = true; }
            }
        }
        return $resp;
    }
    
    public function habilitar($param){
        $resp = false;
        if (isset($param['idmenu'])){
            $obj = new Menu();
            $obj->setear($param['idmenu'], null, null, null, null);
            if($obj->cargar()){
                $obj->setMeDeshabilitado(null);
                if($obj->modificar()){ $resp = true; }
            }
        }
        return $resp;
    }
    
    public function obtenerMenuPorRol($idRol){
        $menus = [];
        $base = new BaseDatos();
        // Solo menus no deshabilitados
        $sql = "SELECT m.* FROM menu m INNER JOIN menurol mr ON m.idmenu = mr.idmenu WHERE mr.idrol = ".$idRol." AND m.medeshabilitado IS NULL";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                while ($row2=$base->Registro()) {
                    $obj=new Menu();
                    $obj->setear($row2['idmenu'],$row2['menombre'],$row2['medescripcion'],$row2['idpadre'],$row2['medeshabilitado']);
                    array_push($menus, $obj);
                }
            }
        }
        return $menus;
    }
}
?>