<?php
	include('../../model/ModelDescuentoDeposito.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelDescuentoDeposito = new ModelDescuentoDeposito();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	
	$modelDescuentoDeposito->eliminar($id);
?>