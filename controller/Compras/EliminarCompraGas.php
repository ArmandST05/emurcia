<?php
	include('../../model/ModelCompra.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelCompras = new ModelCompra();

	$id = $_POST["id"];

	$modelCompras->eliminarGas($id);

?>