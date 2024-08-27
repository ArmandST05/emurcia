<?php
	include('../../model/ModelNomina.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelNomina = new ModelNomina();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$eliminar = $modelNomina->eliminar($id);
	
	if($eliminar){
	    return http_response_code(200);
	}else{
	    return http_response_code(500);
	}
?>