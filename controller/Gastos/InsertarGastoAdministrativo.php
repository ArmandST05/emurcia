<?php
	include('../../model/ModelGasto.php');
	$modelGasto = new ModelGasto();

	$mes = $_POST["mes"];
	$anio = $_POST["anio"];
	$origen = $_POST["origen"];
	$concepto = $_POST["concepto"];
	$cantidad = $_POST["cantidad"];
	$observaciones = $_POST["observaciones"];
	$zona = $_POST["zona"];
	$zonaId = $_POST["zonaId"];

	//Validar no nulos
	if(strlen($mes) < 1 || strlen($anio) < 1 || strlen($origen) < 1 || strlen($concepto) < 1 || 
		strlen($cantidad) < 1 || strlen($zonaId) < 1){
		echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=gastosadministrativos/nuevo.php';
			  </script>";
	}
	else{
		$modelGasto->agregarGastoAdministrativo($mes,$anio,$origen,$concepto,$cantidad,$observaciones,$zona,$zonaId);
		echo "<script> 
			alert('Gasto registrado');
			window.location.href = '../../view/index.php?action=gastosadministrativos/index.php';
		</script>";
	}				
?>

 