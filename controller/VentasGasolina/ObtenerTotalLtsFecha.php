<?php
    include('../../model/ModelVentaGasolina.php');  
    include('../../view/session1.php'); 
    $modelVentaGasolina = new ModelVentaGasolina();

    //Obtener total de ventas por día
    $totalVentas = $modelVentaGasolina->totalesVentaRutaProductoFecha($_GET["rutaId"],$_GET["productoId"],$_GET["fecha"]);
    $totalLitros = 0;
    if(!empty($totalVentas)){
        $totalVentas = reset($totalVentas); 
        $totalLitros = floatval($totalVentas["cantidad"]);
    }
    $datos["totalLitros"] = $totalLitros;
    
    echo json_encode($datos);
?>