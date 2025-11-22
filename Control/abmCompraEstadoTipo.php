<?php
class abmCompraEstadoTipo {
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompraestadotipo']))
                $where.=" and idcompraestadotipo =".$param['idcompraestadotipo'];
            if  (isset($param['cetdescripcion']))
                $where.=" and cetdescripcion ='".$param['cetdescripcion']."'";
        }
        $arreglo = CompraEstadoTipo::listar($where);
        return $arreglo;
    }
}
?>