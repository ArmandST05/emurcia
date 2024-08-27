<?php
    include('../../model/ModelRuta.php'); 

    $modelRuta = new ModelRuta();
    $rutaId = $_GET['rutaId'];
    $vendedores = $modelRuta->obtenerVendedores($rutaId);

    echo json_encode($vendedores);
?>