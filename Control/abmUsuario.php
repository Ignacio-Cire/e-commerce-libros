
<?php


class abmUsuario {

    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){

            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
            if  (isset($param['usnombre']))
                $where.=" and usnombre ='".$param['usnombre']."'";
            if  (isset($param['usmail']))
                $where.=" and usmail ='".$param['usmail']."'";
            if  (isset($param['uspass']))
                $where.=" and uspass ='".$param['uspass']."'";
        }
        $arreglo = Usuario::listar($where);
        return $arreglo;
    }

    public function alta($param){
        $resp = false;
        
        // 1. Verificamos si el usuario ya existe , esto movi del accion
        $existe = $this->buscar(['usnombre' => $param['usnombre']]);
        
        if(count($existe) == 0){
            $obj = new Usuario();
            
            // 2. Encriptamos la contraseña AQUÍ --> esto tambien movi del accion
          
            $passEncriptada = md5($param['uspass']); 
            
            $obj->setear(null, $param['usnombre'], $passEncriptada, $param['usmail'], null);
            
            if ($obj->insertar()){ 
                $resp = true; 
                
                // 3. Asignar el Rol por defecto (Cliente / 2)
                // Necesitamos el ID del usuario recién creado
                // (Como insertar no devuelve ID, lo buscamos)
                $nuevoUserArr = $this->buscar(['usnombre' => $param['usnombre']]);
                
                if(count($nuevoUserArr) > 0){
                    $nuevoObjUser = $nuevoUserArr[0];
                    $idNuevoUsuario = $nuevoObjUser->getIdUsuario();
                    
                    // Llamamos a la función privada para asignar rol
                    $this->asignarRolDefecto($idNuevoUsuario);
                }
            }
        }
        return $resp;
    }


    // Función auxiliar privada para conectar con el otro ABM
    private function asignarRolDefecto($idUsuario){
        $abmRol = new abmUsuarioRol();
        // Le pasamos los IDs crudos, que es lo que tu ABM Rol espera
        $datosRol = [
            'idusuario' => $idUsuario,
            'idrol' => 2 // 2 es Cliente por defecto
        ];
        $abmRol->alta($datosRol);
    }

    public function baja($param){ 
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $obj = new Usuario();
            $obj->setear($param['idusuario'], null, null, null, null);
            if ($obj->cargar()){
                $obj->setUsDeshabilitado(date('Y-m-d H:i:s'));
                if($obj->modificar()){ $resp = true; }
            }
        }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
             $obj = new Usuario();
             $obj->setear($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail'], null);
             if($obj->modificar()){ $resp = true; }
        }
        return $resp;
    }

    private function seteadosCamposClaves($param){
        return isset($param['idusuario']);
    }
}
?>