<?php
	include('../../model/ModelAutoconsumo.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelAutoconsumo = new ModelAutoconsumo();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$modelAutoconsumo->eliminar($id);

	echo "<script> 
	         alert('Eliminado correctamente'); 
			window.location.href = '../../view/autoconsumos/index.php'; 
		  </script>";
?>