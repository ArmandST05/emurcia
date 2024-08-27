<?php
	include('../../model/ModelDesviacionVolumen.php');	
  	/*Variable para llamar metodo de Modelo*/
	$modelDesviacionVolumen = new ModelDesviacionVolumen();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$modelDesviacionVolumen->eliminar($id);

	echo "<script> 
	         alert('Eliminado correctamente'); 
			window.location.href = '../../view/desviacionvolumen/index.php'; 
		  </script>";
?>