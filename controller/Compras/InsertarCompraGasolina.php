<?php
	include('../../view/session1.php');
	include('../../model/ModelCompraGasolina.php');
	$modelCompras = new ModelCompraGasolina();
	date_default_timezone_set('America/Mexico_City');

	//Obtenemos los datos
	$fecha = $_POST["fecha"];
	$productoId = $_POST["producto"];

	$numFactura = $_POST["numFactura"];
	$chofer = strtoupper($_POST["chofer"]);
	$litros = $_POST["litros"];
	if ($_SESSION["tipoUsuario"] == "su"){
		$zonaId = $_POST["zona"];
	}else{
		$zonaId = $_SESSION["zonaId"];
	}
    $aceite = $_POST["aceite"];

    $precio = $_POST["precio"];
    $importe = $_POST["importe"];
    $fechaDescarga = $_POST["fechaDescarga"];
	$tarifa = $_POST["tarifa"];
    
  	$fechaPago = date('Y-m-d',strtotime('+5 days', strtotime($fecha)));
	$compra = $modelCompras->insertarCompraGasolina($fecha,$productoId,$numFactura,$chofer,$litros,$zonaId,$aceite,$precio,$importe,$fechaDescarga,$fechaPago,$tarifa);
	if($compra){
		echo "<script> 
			alert('Compra registrada');
			window.location.href = '../../view/index.php?action=comprasgasolina/index.php'; 
		</script>";
	}
	else{
		echo "<script> 
			alert('Ocurrió un error al registrar la compra');
			window.location.href = '../../view/index.php?action=comprasgasolina/index.php'; 
		</script>";
	}
?>
