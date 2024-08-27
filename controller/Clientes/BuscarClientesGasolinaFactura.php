<?php
    session_start();
    include('../../model/ModelCliente.php');
    $modelCliente = new ModelCliente();

    $zona = $_SESSION["zonaId"];
    $factura = $_GET["q"];
    
    if($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc"){
        $clientes = $modelCliente->buscarClientesGasolinaPorFactura($factura);
    }
    else{
        $clientes = $modelCliente->buscarClientesGasolinaPorZonaFactura($factura, $zona);  
    }
    echo json_encode($clientes);
?>