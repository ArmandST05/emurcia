<?php
    include('../../model/ModelClientePedido.php');  
    $modelClientePedido = new ModelClientePedido();

	$datos = $_GET['clienteDatos'];
    $cliente = explode("*", $datos);
    
    $clienteDatos = $modelClientePedido->obtenerPorId($cliente[0]); 

    echo json_encode($clienteDatos);
?>
