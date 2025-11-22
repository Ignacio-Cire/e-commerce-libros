<?php
class Session {
    public function __construct(){
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function iniciar($nombreUsuario, $psw){
        $resp = false;
        $obj = new abmUsuario();
        $param['usnombre'] = $nombreUsuario;
        $param['uspass'] = md5($psw);
        $param['usdeshabilitado'] = 'null';

        $resultado = $obj->buscar($param);
        if(count($resultado) > 0){
            $usuario = $resultado[0];
            $_SESSION['idusuario'] = $usuario->getIdUsuario();
            $_SESSION['usnombre'] = $usuario->getUsNombre();
            
            
            $abmRol = new abmUsuarioRol();
            $listaRoles = $abmRol->buscar(['idusuario' => $usuario->getIdUsuario()]);
            
            if(count($listaRoles) > 0){
                
                $primerRol = $listaRoles[0];
                $_SESSION['rol_activo'] = $primerRol->getObjRol()->getIdRol();
            } else {
                $_SESSION['rol_activo'] = null;
            }
            

            $resp = true;
        }
        return $resp;
    }

    public function activa(){
        if (isset($_SESSION['idusuario'])) {
            return true;
        }
        return false;
    }

    public function getUsuario(){
        $usuario = null;
        if($this->activa()){
            $obj = new abmUsuario();
            $param['idusuario'] = $_SESSION['idusuario'];
            $res = $obj->buscar($param);
            if(count($res) > 0){
                $usuario = $res[0];
            }
        }
        return $usuario;
    }

    public function getRolActivo(){
        return isset($_SESSION['rol_activo']) ? $_SESSION['rol_activo'] : null;
    }

    public function setRolActivo($idrol){
        $_SESSION['rol_activo'] = $idrol;
    }

    public function cerrar(){
        session_unset();
        session_destroy();
    }
}
?>