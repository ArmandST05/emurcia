<?php
    include('../../model/ModelCiudad.php');  

    $modelCiudad = new ModelCiudad();
    $zonaId = $_GET['zonaId'];
    $ciudades = $modelCiudad->listaPorZona($zonaId);
    
    echo json_encode($ciudades);
?>