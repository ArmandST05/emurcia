<?php
    session_start();
    include('../../model/ModelCliente.php');
    $modelCliente = new ModelCliente();

    $zona= $_SESSION["zonaId"];
    $nombre = $_GET["q"];
    
    if($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc"){
        $clientes = $modelCliente->buscarClientesPorNombre($nombre);
    }
    else{
        $clientes = $modelCliente->buscarClientesPorZonaNombre($nombre, $zona);  
    }
    echo json_encode($clientes);
?>