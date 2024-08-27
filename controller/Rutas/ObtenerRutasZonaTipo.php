<?php
    //Incluye todas las rutas no sólo con las que se generan ventas
    include('../../model/ModelRuta.php');  

    $modelRuta = new ModelRuta();
    $zonaId = $_GET['zonaId'];
    $tipoRutaId = $_GET['tipoRutaId'];
    $rutas = $modelRuta->listaPorZonaEstatusTipo($zonaId,1,$tipoRutaId);//Obtener las rutas tipo estación

    echo json_encode($rutas);
?>