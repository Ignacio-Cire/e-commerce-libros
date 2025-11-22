<?php
class abmMenu {
    
    // --- MÉTODO BUSCAR (IGUAL QUE ANTES) ---
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

    // --- MÉTODO ALTA (MODIFICADO: AHORA GESTIONA ROLES) ---
    public function alta($param){
        $resp = false;
        $obj = new Menu();
        $padre = isset($param['idpadre']) && $param['idpadre'] != "" ? $param['idpadre'] : null;
        $obj->setear(null, $param['menombre'], $param['medescripcion'], $padre, null);
        
        if ($obj->insertar()){ 
            $resp = true;
            
            // --- LÓGICA AGREGADA: GESTIONAR ROLES AL CREAR ---
            // Si mandaron roles, necesitamos el ID del menú recién creado.
            if(isset($param['roles']) && is_array($param['roles'])){
                // Como insertar no devuelve ID, lo buscamos por sus datos únicos
                $filtro = [
                    'menombre' => $param['menombre'],
                    'medescripcion' => $param['medescripcion']
                ];
                $busqueda = $this->buscar($filtro);
                
                if(count($busqueda) > 0){
                    // Obtenemos el último creado que coincida
                    $nuevoMenu = end($busqueda);
                    $idNuevo = $nuevoMenu->getIdMenu();
                    
                    // Llamamos a la función privada
                    $this->gestionarRoles($idNuevo, $param['roles']);
                }
            }
        }
        return $resp;
    }

    // --- MÉTODO MODIFICACION (MODIFICADO: AHORA GESTIONA ROLES) ---
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
                if($obj->modificar()){ 
                    $resp = true; 
                    
                    // --- LÓGICA AGREGADA: GESTIONAR ROLES AL EDITAR ---
                    if(isset($param['roles'])){
                        $this->gestionarRoles($param['idmenu'], $param['roles']);
                    }
                }
            }
        }
        return $resp;
    }

    // --- MÉTODO BAJA (IGUAL QUE ANTES - BORRADO LÓGICO) ---
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
    
    // --- MÉTODO HABILITAR (IGUAL QUE ANTES) ---
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
    
    // --- MÉTODO OBTENER POR ROL (IGUAL QUE ANTES) ---
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

    // --- NUEVO MÉTODO PRIVADO: EL CEREBRO DE LOS ROLES ---
    // Este método encapsula la lógica sucia que antes tenías en la Acción
    private function gestionarRoles($idMenu, $roles){
        $abmMenuRol = new abmMenuRol();
        
        // 1. Buscar roles actuales de este menú
        $rolesActuales = $abmMenuRol->buscar(['idmenu' => $idMenu]);
        
        // 2. Eliminarlos todos (limpieza)
        foreach($rolesActuales as $mr){
            // Asumiendo que el objeto MenuRol tiene el método eliminar o usamos el ABM
            // Si tu objeto MenuRol NO tiene eliminar(), usa: $abmMenuRol->baja(['idmenu'=>$idMenu, 'idrol'=>$mr->getObjRol()->getIdRol()]);
            $mr->eliminar(); 
        }

        // 3. Insertar los nuevos marcados en el checkbox
        if(is_array($roles)){
            foreach($roles as $idRol){
                // Armamos los objetos necesarios para el ABM de MenuRol
                $objMenu = new Menu();
                $objMenu->setear($idMenu, null, null, null, null);
                
                $objRol = new Rol();
                $objRol->setear($idRol, null);
                
                // Llamamos al alta del ABM vecino
                $abmMenuRol->alta(['objMenu' => $objMenu, 'objRol' => $objRol]);
            }
        }
    }
}
?>