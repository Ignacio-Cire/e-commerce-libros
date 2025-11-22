<?php
class abmCompra {
    
    // Busca una compra activa (Estado 5: Carrito) para el usuario
    public function buscarCarrito($idUsuario){
        $objCompra = null;
        // Buscamos todas las compras del usuario
        $listaCompras = $this->buscar(['idusuario'=>$idUsuario]);
        
        foreach($listaCompras as $compra){
            $abmCE = new abmCompraEstado();
            // Buscamos el estado activo (fecha fin null) de esa compra
            $listaCE = $abmCE->buscar(['idcompra'=>$compra->getIdCompra(), 'cefechafin'=>'null']);
            if(count($listaCE) > 0){
                // Si el estado activo es 5 (Carrito), retornamos esta compra
                if($listaCE[0]->getIdCompraEstadoTipo() == 5){
                    $objCompra = $compra;
                    break; // Encontramos el carrito, cortamos
                }
            }
        }
        return $objCompra;
    }

    public function iniciarCompra($idUsuario){
        $objC = new Compra();
        // Crea la compra con fecha actual y el ID de usuario
        $objC->setear(null, date('Y-m-d H:i:s'), $idUsuario); 
        
        if($objC->insertar()){
            // Obtenemos el ID de la compra recién creada
            $idCompra = $objC->getIdCompra();
            
            // Crear estado inicial 5 (Carrito)
            $objCE = new CompraEstado();
            // setear(id, idcompra, idtipo, fechaini, fechafin)
            $objCE->setear(null, $idCompra, 5, date('Y-m-d H:i:s'), null);
            $objCE->insertar();
            
            return $objC;
        }
        return null;
    }
    
    public function buscar($param){
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idcompra']))
                $where.=" and idcompra =".$param['idcompra'];
            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
        }
        $arreglo = Compra::listar($where);
        return $arreglo;
    }
}
?>