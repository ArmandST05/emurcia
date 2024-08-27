<?php
	include('../../model/ModelClientePedido.php');	
	$modelCliente = new ModelClientePedido();

	$id = $_POST["id"];
	$nombre = $_POST["nombre"];
	$direccion = $_POST["direccion"];
	$telefono = $_POST["telefono"];
	$proximaFecha = $_POST["proximaFecha"];
	$productoId = $_POST["producto"];
	$rfc = NULL;

	$cliente = $modelCliente->obtenerClientePorId($id);	
	$cliente = reset($cliente);

	//Calcular próximo pedido MANUAL
	//La periodicidad manual se calcula con la diferencia de la penúltima y última fecha de los pedidos del cliente.
	//Al editar la próxima fecha del cliente en el apartado de clientes se calcula la diferencia entre la fecha del último
	//pedido y la fecha que ingresó el usuario.

	$fechaUltimoPedido = $cliente["fecha_ultimo_pedido"];
	
	$fechaUPedido = date_create($fechaUltimoPedido);//Fecha de Último Pedido
	$fechaProPedido = date_create($proximaFecha);//Fecha nueva que ingresó el usuario.
	
    $diferencia = date_diff($fechaUPedido,$fechaProPedido);
	
	if(($diferencia->format("%a"))=="0") $periodicidadManual = "22"; 
	else $periodicidadManual = $diferencia->format("%a");	

	//Calcular próximo pedido MANUAL
	$modelCliente->actualizarCliente($id,$nombre,$direccion,$telefono,$rfc,$periodicidadManual,$productoId);
				        
	echo $periodicidad;
	//periodicidad sistema
	//Calculada por sistema
	//Calculada por usuario

?>