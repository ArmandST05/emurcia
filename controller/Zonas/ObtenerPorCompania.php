<?php
    include('../../model/ModelZona.php');  
    $modelZona = new ModelZona();
    $zonas = $modelZona->obtenerPorCompaniaEstatus($_GET['companiaId'],$_GET['estatusId']);
    echo json_encode($zonas);
?>