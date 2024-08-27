<?php
	date_default_timezone_set('America/Mexico_City');

	include('../../model/ModelPedido.php'); 
	include('../../model/ModelClientePedido.php');	
	$modelPedido = new ModelPedido();
	$modelCliente = new ModelClientePedido();

	$id = $_POST['pedidoId'];
	$folio = $_POST['folioNota'];
	$total = $_POST['totalCantidad'];
	$hora = date('Y-m-d H:i:s');
	
	//Obtener el pedido atendido para obtener el cliente
	$pedidoAtendido = $modelPedido->obtenerPorId($id);
	$clienteId = $pedidoAtendido["cliente_id"];//Obtener el cliente para calcular próximos pedidos

	//Si el pedido fue por ruteo/perifoneo entonces se registra la hora de aviso a unidad igual a la hora de atención
	if($pedidoAtendido['tipo_contacto_pedido_id'] == 2){
		$viaInforme = 3;//PERIFONEO
		$modelPedido->avisarRuta($id,$hora,$viaInforme,0);
	}

	//Atender pedido (Cambiar estatus)
	$pedidoActualizado = $modelPedido->atender($id,$hora,$folio,$total);

	if($pedidoActualizado){

		//CALCULAR PRÓXIMO PEDIDO 
		//Obtener datos de los pedidos
		$ultimosPedidosCliente = $modelPedido->obtenerUltimos2PedidosPorClienteId($clienteId);//Último y penúltimo pedido del cliente
		$primerPedidoCliente = $modelPedido->obtenerTotalPedidosPorClienteId($clienteId);//Primer pedido del cliente

		//Obtener total de pedidos
		$totalPedidos = $modelPedido->obtenerTotalPedidosPorClienteId($clienteId);
		$totalPedidos = reset($totalPedidos);
		$totalPedidos = $totalPedidos["total_pedidos"];

		//-----------------------------------------------------------------------
		//PRÓXIMO PEDIDO SISTEMA
		$ultimoPedido = $ultimosPedidosCliente[0];
		$primerPedido = reset($primerPedidoCliente);

		//Obtener datos del cliente
		//$productoNombre = $ultimoPedido["producto_id"];

		$pedidosAnteriores = $modelPedido->obtenerPedidosAnterioresClienteId($clienteId);

		if($totalPedidos >1){
			$penultimoPedido = $ultimosPedidosCliente[1];
			$fechaUltimoPedido = date_create($ultimoPedido["fecha_pedido"]);
			$fechaPrimerPedido = date_create($primerPedido["fecha_pedido"]);

			$intervalo = date_diff($fechaUltimoPedido, $fechaPrimerPedido)->days;
			$periodicidadSistema = round($intervalo/$totalPedidos);

			if($periodicidadSistema == 0) $periodicidadSistema = "22"; 
		}
		else $periodicidadSistema = "22"; 

		$modelCliente->actualizarPeriodicidadSistemaClienteId($clienteId,$periodicidadSistema,$ultimoPedido["fecha_pedido"]);
		
		//PRÓXIMO PEDIDO MANUAL

		//La periodicidad manual se calcula con la diferencia de la penúltima y última fecha de los pedidos del cliente.
		//Al editar la próxima fecha del cliente en el apartado de clientes se calcula la diferencia entre la fecha del último
		//pedido y la fecha que ingresó el usuario.

		if($totalPedidos >1){
			$fechaUltimoPedido = date_create($ultimoPedido["fecha_pedido"]);
			$fechaPenultimoPedido = date_create($penultimoPedido["fecha_pedido"]);
			$intervalo = date_diff($fechaPenultimoPedido,$fechaUltimoPedido);
			
			if(($intervalo->format("%a"))=="0") $periodicidadManual = "22"; 
			else $periodicidadManual = $intervalo->format("%a");	
		}
		else $periodicidadManual = "22";
		
		$modelPedido->actualizarPeriodicidadManualClienteId($clienteId,$periodicidadManual);	

		//Este cálculo también se encuentra en la API, actualizar en ambos lugares.
		
		echo "<script>
			window.location.href = '../../view/index.php?action=pedidos/index.php';
			</script>";
	}
	else{
		echo "<script>
				alert('Ha ocurrido un error al registrar la atención del pedido');
				window.location.href = '../../view/index.php?action=pedidos/index.php';
			</script>";
	}
?>