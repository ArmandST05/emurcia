<?php
	include('../../model/ModelRuta.php');	
  	//Variable para llamar método de Modelo
	$modelRuta = new ModelRuta();

	/*Obtenemos los datos*/
	$rutaId = $_POST["rutaId"];
	$telefono = $_POST["rutaTelefono"];
	if(empty($telefono)) $telefono = null;
	$vendedor1 = (isset($_POST["vendedorSelect"]) && !empty($_POST["vendedorSelect"])) ? $_POST["vendedorSelect"] : null;
	$vendedor2 = (isset($_POST["ayudanteSelect"]) && !empty($_POST["ayudanteSelect"])) ? $_POST["ayudanteSelect"] : null;

	if(strlen($rutaId)<1){
		echo "<script> 
				alert('Ingresa todos los datos');
				window.location.href = '../../view/index.php?action=rutas/index.php';
			</script>";
	}else if ($vendedor1 == $vendedor2 && $vendedor1) {
		echo "<script> 
			alert('Ambos vendedores no pueden ser el mismo empleado');
			window.location.href = '../../view/index.php?action=rutas/index.php';
			</script>";
	} else{
		$modelRuta->actualizarTelefono($rutaId,$telefono);
		$modelRuta->actualizarVendedores($rutaId,$vendedor1, $vendedor2);
		echo "<script> 
				window.location.href = '../../view/index.php?action=rutas/index.php'; 
				</script>";
	}
?>

