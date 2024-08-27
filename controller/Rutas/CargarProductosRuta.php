<?php
    include('../../model/ModelProducto.php');  
    include('../../model/ModelRuta.php'); 
    include('../../model/ModelZona.php'); 
    include('../../view/session1.php'); 

    $modelProducto = new ModelProducto();
    $modelZona = new ModelZona();
    $modelRuta = new ModelRuta();
    $ruta = $modelRuta->obtenerRutaId($_GET['rutaId']);
    $zona = $modelZona->obtenerZonaId($ruta['zona_id']);
    $rutaId = $_GET['rutaId'];
    $tipoZonaId = $zona["tipo_zona_id"];
    $productos = $modelProducto->productosPorRuta($rutaId, $tipoZonaId);

    echo json_encode($productos);
?>