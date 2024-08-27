<?php
    include('../../model/ModelRuta.php');  
    include('../../view/session1.php'); 
    $modelRuta = new ModelRuta();

    $rutaId = $_GET["rutaId"];

    $lecturaInicial = 0;
    $porcentajeInicial = 0;

    //Obtener últimas lecturas de la ruta (se obtienen de la última venta)
    $lecturasData = $modelRuta->obtenerUltimasLecturasRutaId($rutaId);

    if(!empty($lecturasData)){
        $lecturasData = reset($lecturasData); 
        $lecturaInicial = $lecturasData["lectura_final"];
        $porcentajeInicial = $lecturasData["porcentaje_final"];
    }
    $datos["lecturaInicial"] = $lecturaInicial;
    $datos["porcentajeInicial"] = $porcentajeInicial;
    
    echo json_encode($datos);
?>