<?php
	include('../../model/ModelZona.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelZona = new ModelZona();

	/*Obtenemos los datos*/
	$zonaId = $_POST["zonaId"];
	$valorPunto = $_POST["valorPunto"];

	$zona = $modelZona->actualizarValorPunto($zonaId,$valorPunto);
	echo "<script> 
			alert('Valor de punto actualizado');
			window.location.href = '../../view/index.php?action=sistema-puntos/capturar-valor-puntos.php'; 
			</script>";

?>

