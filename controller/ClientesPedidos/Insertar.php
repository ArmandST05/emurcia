<?php
   	include('../../view/session1.php');
	include('../../model/ModelClientePedido.php');	
	$modelClientePedido = new ModelClientePedido();
	
	/*Obtenemos los datos*/
    $nombre = trim($_POST['nombre']);
    $direccion = trim($_POST['direccion']);
    $colonia = trim($_POST['colonia']);
    $telefono = trim($_POST['telefono']);
    $zonaId = $_SESSION['zonaId'];
    $referencias = null;

	//Si ya existe un cliente con ese nombre y dirección se utiliza
	$clienteExistente = $modelClientePedido->obtenerPorNombreDireccion($nombre,$direccion);
	if(!empty($clienteExistente)){
		$clienteId = $clienteExistente["idclientepedido"];
	}
	else{
		$clienteId = $modelClientePedido->insertar($nombre,$direccion,$colonia,$telefono,$zonaId,$referencias = NULL);
	}

	?>

