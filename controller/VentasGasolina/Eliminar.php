<?php
	include('../../model/ModelVentaGasolina.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelVentaGasolina = new ModelVentaGasolina();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$modelVentaGasolina->eliminar($id);

	echo "<script> 
	        alert('Eliminado correctamente'); 
			window.location.href = '../../view/ventasgasolina/index.php'; 
		</script>";
?>