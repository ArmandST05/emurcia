<?php
	include('../../model/ModelPedido.php');	
	$modelPedido = new ModelPedido();
	
	$id = $_POST['pedidoId'];
	$modelPedido->cancelarPedido($id);

	echo $id;
?>