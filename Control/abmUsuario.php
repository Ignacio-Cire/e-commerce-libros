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
        $obj = new Usuario();
       
        $obj->setear(null, $param['usnombre'], $param['uspass'], $param['usmail'], null);
        if ($obj->insertar()){ $resp = true; }
        return $resp;
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