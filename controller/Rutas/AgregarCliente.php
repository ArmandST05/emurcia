<?php
	include('../../model/ModelRuta.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelRuta = new ModelRuta();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$zona = $_POST["zona"];
	$ruta = $_POST["ruta"];
	$nombre = $_POST["nombre"];

	$modelRuta->agregarClienteRuta($id,$ruta);
	echo "<script>   
			window.location.href = '../../view/index.php?action=rutas/editar.php&zona=".$zona."&id_ruta=".$ruta."&name=".$nombre."'; 
		  </script>";
?>