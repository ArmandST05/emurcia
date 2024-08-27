<?php
	include('../../model/ModelVenta.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelVenta = new ModelVenta();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$eliminar = $modelVenta->eliminar($id);
	
	if($eliminar){
	    return http_response_code(200);
	}else{
	    return http_response_code(500);
	}
?>