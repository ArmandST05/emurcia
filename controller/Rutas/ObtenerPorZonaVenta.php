<?php
    include('../../model/ModelRuta.php');  

    $modelRuta = new ModelRuta();
    $zonaId = $_GET['zonaId'];
    $rutas = $modelRuta->listaPorZonaVenta($zonaId);

    echo json_encode($rutas);
?>