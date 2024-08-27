<?php

include('../../model/ModelCliente.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelCliente = new ModelCliente();

	$id = $_POST["id"];

	$modelCliente->eliminarcliente($id);

	echo "<script> 
			alert('Cliente eliminado');
			window.location.href = '../../view/index.php?action=clientes/index_credito.php'; 
		  </script>";
?>