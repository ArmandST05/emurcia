<?php
    include('../../model/ModelRuta.php');  

    $modelRuta = new ModelRuta();
    $zonaId = $_GET['zonaId'];
    $rutas = $modelRuta->zonaIndex($zonaId);

    echo json_encode($rutas);
?>