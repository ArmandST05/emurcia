<?php

include('../../model/ModelCompra.php');	
  	/*variable para llamar metodo de Modelo*/
	$mode_compras = new ModelCompra();

	$id=$_POST["id"];

	$mode_compras->eliminarGas($id);

	echo "<script> 
			alert('Compra eliminada correctamente');
			window.location.href = '../../view/compras/index_compras.php'; 
		  </script>";
?>