<?php
    //Incluye todas las rutas no sólo con las que se generan ventas
    include('../../model/ModelRuta.php');  

    $modelRuta = new ModelRuta();
    $zonaId = $_GET['zonaId'];
    $rutas = $modelRuta->listaPorZonaEstatus($zonaId,1);

    echo json_encode($rutas);
?>