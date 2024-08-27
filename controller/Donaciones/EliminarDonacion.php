<?php
	include('../../model/ModelDonacion.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelDonacion = new ModelDonacion();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$modelDonacion->eliminar($id);
?>