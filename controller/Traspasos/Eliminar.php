<?php
	include('../../model/ModelTraspaso.php');	
	$modelTraspaso = new ModelTraspaso();
	$id = $_POST["id"];
	$eliminado = $modelTraspaso->eliminar($id);
?>