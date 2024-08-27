<?php
	include('../../model/ModelClientePedido.php');	
	include('../../model/ModelPedido.php');	
	$modelCliente = new ModelClientePedido();
	$modelPedido = new ModelPedido();

	$pedidoId = $_POST["pedidoId"];
	date_default_timezone_set('America/Mexico_City');
	$fecha2 = date('Y-m-d');

	//Calcular próximo pedido
	if($pedidoId) $ultimoPedido = $modelPedido->obtenerPorId($pedidoId);

	$cliente = $modelCliente->obtenerClientePorNombre($ultimoPedido["cliente_nombre"]);	  
	$cliente = reset($cliente);
	$clienteId = $cliente["idcliente"];

	$fecha1 = $ultimoPedido["fecha_pedido"];

    $fecha_1 = date_create($fecha1);
    $fecha_2 = date_create($fecha2);

    $diff = date_diff($fecha_1,$fecha_2);
    if(($diff->format("%a"))=="0"){
    	$diff="22";
    }else{
    $diff = $diff->format("%a");	
    }		
	$modelPedido->actualizarPeriodicidad($fecha1,$fecha2,$clienteId,$diff);	
	echo $fecha1."***".$fecha2."***".$diff;				
?>

 