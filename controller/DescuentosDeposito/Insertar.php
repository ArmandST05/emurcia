<?php
	include('../../view/session1.php');
	include('../../model/ModelDescuentoDeposito.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelDescuentoDeposito = new ModelDescuentoDeposito();
	date_default_timezone_set('America/Mexico_City');

	/*Obtenemos los datos*/
	$fecha = ($_POST["fecha"]) ? $_POST["fecha"]: date('Y-m-d');

	$zonaId = floatval($_POST["zonaId"]);
    $pagoElectronico = floatval($_POST["pagoElectronico"]);
	$valeRetiro = floatval($_POST["valeRetiro"]);
	$descripcionValeRetiro = $_POST["descripcionValeRetiro"];
	$gastos = floatval($_POST["gastos"]);
	$cheque = floatval($_POST["cheque"]);
	$otrasSalidas = floatval($_POST["otrasSalidas"]);
  
	$total = $pagoElectronico + $valeRetiro + $gastos + $cheque + $otrasSalidas;

	if($total <= 0){
		echo "<script> 
	         alert('Ingresa las cantidades correctas'); 
			window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
		  </script>";
	}
	else{
	$descuentoDeposito = $modelDescuentoDeposito->insertar($fecha,$zonaId,$pagoElectronico,$valeRetiro,$descripcionValeRetiro,$gastos,$cheque,$otrasSalidas);

	echo "<script> 
	         alert('Detalle agregado correctamente'); 
			window.location.href = '../../view/index.php?action=descuentosdeposito/index.php&zona=".$zonaId."';
		  </script>";
	}
?>