<?php
	include('../../model/ModelGasto.php');
	$modelGasto = new ModelGasto();

	$id = $_POST["id"];
	$mes = $_POST["mes"];
	$anio = $_POST["anio"];
	$rutaId = $_POST["ruta"];
	$concepto = $_POST["concepto"];
	$cantidad = $_POST["cantidad"];
	$observaciones = $_POST["observaciones"];
	$zonaId = $_POST["zonaId"];

	//Validar no nulos
	if(strlen($id) < 1 || strlen($mes) < 1 || strlen($anio) < 1 || strlen($rutaId) < 1 || strlen($concepto) < 1 || 
		strlen($cantidad) < 1 || strlen($zonaId) < 1){
		echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=gastosadministrativos/editar.php';
			  </script>";
	}
	else{
		$modelGasto->actualizarGastoRuta($id,$mes,$anio,$rutaId,$concepto,$cantidad,$observaciones,$zonaId);
		echo "<script> 
				alert('Gasto actualizado');
				window.location.href = '../../view/index.php?action=gastosruta/index.php';
			</script>";
	}				
?>

 