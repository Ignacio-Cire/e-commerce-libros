<?php
class abmCompraEstado {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompra']))
                $where.=" and idcompra =".$param['idcompra'];
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo =".$param['idcompraestadotipo'];
            if  (isset($param['cefechafin'])){
                if($param['cefechafin'] == 'null'){
                    $where.=" and cefechafin IS NULL";
                }
            }
        }
        $arreglo = CompraEstado::listar($where);
        return $arreglo;
    }
}
?>