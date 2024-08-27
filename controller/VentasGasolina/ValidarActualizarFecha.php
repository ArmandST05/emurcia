<?php
	include('../../model/ModelVenta.php');	
  	/*Variable para llamar metodo de Modelo*/
	$modelVenta = new ModelVenta();

	$ventaId = $_GET["ventaId"];
	$rutaId = $_GET["rutaId"];
	$fecha = $_GET["nuevaFecha"];
	
	//Verificar si no hay ventas en una fecha posterior a la nueva fecha en que se busca actualizar la venta
	$totalVentasPosteriores = $modelVenta->obtenerTotalVentasPosterioresFecha($ventaId,$rutaId,$fecha);
	if(isset($totalVentasPosteriores)) {
		echo json_encode($totalVentasPosteriores[0]['total_ventas']);
	}
	else http_response_code(500);
?>