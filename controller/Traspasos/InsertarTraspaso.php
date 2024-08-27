<?php
	date_default_timezone_set('America/Mexico_City');
	include('../../view/session1.php');	
	include('../../model/ModelTraspaso.php');	
  	/*Variable para llamar método de Modelo*/
	$modelTraspaso = new ModelTraspaso();
	date_default_timezone_set('America/Mexico_City');
	
	/*Obtenemos los datos*/
	$fecha = date("Y-m-d");
	$zonaOrigen = $_SESSION["zonaId"];
    $zonaDestino = $_POST["zonaDestino"];
    $cantidad= $_POST["cantidad"];
	
	if(empty($zonaOrigen) || empty($zonaDestino) || empty($cantidad)){
	echo "<script> 
			alert('Ingresa todos los datos por favor');
			window.location.href = '../../view/index.php?action=traspasos/nuevo.php';
		  </script>";
	}
	else{
		$nuevoTraspaso = $modelTraspaso->insertar($fecha,$zonaOrigen,$zonaDestino,$cantidad);
		if($nuevoTraspaso){
			echo "<script> 
				alert('Traspaso agregado'); 
				window.location.href = '../../view/index.php?action=traspasos/index.php'; 
			</script>";
		}else{
		echo "<script> 
				alert('No se agregó el traspaso, intenta nuevamente'); 
				window.location.href = '../../view/index.php?action=traspasos/index.php'; 
			</script>";
		}
	}
?>