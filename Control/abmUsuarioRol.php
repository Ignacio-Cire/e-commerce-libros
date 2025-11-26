<?php
class abmUsuarioRol {
    
   
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
            if  (isset($param['idrol']))
                $where.=" and idrol =".$param['idrol'];
        }
        return UsuarioRol::listar($where);
    }

    
public function alta($param){
        $resp = false;
        
        // Verificamos que vengan los datos necesarios
        if(isset($param['idusuario']) && isset($param['idrol'])){
            
            // 1. Validar: ¿Ya tiene ese rol?
            $rolEncontrado = $this->buscar([
                'idusuario' => $param['idusuario'], 
                'idrol' => $param['idrol']
            ]);

            // Si NO existe (count es 0), procedemos
            if (count($rolEncontrado) == 0) {
                
                // 2. Crear los objetos AQUI adentro
                $objUser = new Usuario();
                $objUser->setear($param['idusuario'], null, null, null, null);
                
                $objRol = new Rol();
                $objRol->setear($param['idrol'], null);

                $objUsuarioRol = new UsuarioRol();
                // Ojo aquí: setear espera OBJETOS, no IDs. Por eso los creamos arriba.
                $objUsuarioRol->setear($objUser, $objRol);

                // 3. Insertar
                if ($objUsuarioRol->insertar()){
                    $resp = true;
                }
            }
        }
        
        return $resp;
    }
}
?>