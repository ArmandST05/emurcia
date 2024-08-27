<?php
include('../../model/ModelClienteDescuento.php');	
  	/*Variable para llamar metodo de Modelo*/
	$modelCliente = new ModelClienteDescuento();
	$cliente = $modelCliente->actualizarEstatus($_POST["id"],$_POST["estatusId"]);

	if($cliente) http_response_code(200);
	else http_response_code(500);
?>