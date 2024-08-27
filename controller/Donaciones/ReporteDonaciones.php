<?php
	include('../../view/session1.php');	
	include('../../model/ModelDonacion.php');	
	$modelDonacion = new ModelDonacion();

	/*Obtenemos los datos*/
    $fechaInicial = $_POST["fechaInicial"];
	$fechaFinal = $_POST["fechaFinal"];

	if($_SESSION["tipoUsuario"] == "su") {
		$zonaId = $_POST["zonaId"];
		$zonaNombre = $_POST["zonaNombre"];
	}
	else{
		$zonaId = $_SESSION["zonaId"];
		$zonaNombre = $_SESSION["zona"];
	}

	$modelDonacion->crearPdfDonacionesZonaEntreFechas($fechaInicial, $fechaFinal,$zonaId,$zonaNombre);
?>