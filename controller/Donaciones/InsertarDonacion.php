<?php
	include('../../view/session1.php');
	include('../../model/ModelDonacion.php');	
  	/* Variable para llamar método de Modelo*/
	$modelDonacion = new ModelDonacion();

	/*Obtenemos los datos*/
    $fecha = $_POST["fecha"];
    $cantidad = $_POST["cantidad"];
	$zonaId = $_SESSION["zonaId"];
	$comentario = $_POST["comentario"];
	
	$modelDonacion->insertar($fecha,$cantidad,$zonaId,$comentario);

	echo "<script> 
	         alert('Donación agregada correctamente'); 
			window.location.href = '../../view/index.php?action=donaciones/index.php'; 
		  </script>";
?>