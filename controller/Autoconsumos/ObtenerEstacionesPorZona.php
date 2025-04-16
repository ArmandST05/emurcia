<?php
    include('../../model/ModelAutoconsumo.php');  

    $modelAutoconsumo = new ModelAutoconsumo();
    $zonaId = $_GET['zonaId'];
    $rutas = $modelAutoconsumo->obtenerAutoconsumosPorFechaEstaciones($zonaId);

    echo json_encode($rutas);
?>