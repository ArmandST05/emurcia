<?php
    include('../../model/ModelCompraGasolina.php');  
    include('../../view/session1.php'); 
    $modelComprasGasolina = new ModelCompraGasolina();

    //Obtener total de ventas por día
    $totalCompras = $modelComprasGasolina->obtenerTotalZonaProductoFechaDescarga($_GET["zonaId"],$_GET["productoId"],$_GET["fecha"]);
    $totalLitros = 0;
    if(!empty($totalCompras)){
        $totalCompras = reset($totalCompras); 
        $totalLitros = floatval($totalCompras["total_litros"]);
    }
    $datos["totalLitros"] = $totalLitros;
    
    echo json_encode($datos);
?>