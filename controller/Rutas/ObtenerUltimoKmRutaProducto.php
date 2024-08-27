<?php
    include('../../model/ModelAutoconsumo.php');  
    $modelAutoconsumo = new ModelAutoconsumo();

    $rutaId = $_GET["rutaId"];
    $productoNombre = $_GET["productoNombre"];
    $kmInicial = 0;

    //Obtener último kilometraje de la ruta (se obtienen del último autoconsumo registrado)
    $kmData = $modelAutoconsumo->obtenerUltimoKilometrajeRutaProducto($rutaId,$productoNombre);

    if(!empty($kmData)){
        $kmData = reset($kmData); 
        $kmInicial = $kmData["km_fin"];
    }
    echo json_encode($kmInicial);
?>