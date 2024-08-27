<?php
    include('../../model/ModelZona.php');  
    $modelZona = new ModelZona();

	$ciudad = $_GET['ciudad'];

    $zonaCliente = $modelZona->buscarZonaNombre($ciudad);
    $zonaCliente = reset($zonaCliente);
    
    echo $zonaCliente["idzona"];
?>

