<?php
    include('../../model/ModelEmpleado.php');  

    $modelEmpleado = new ModelEmpleado();
    $zonaId = $_GET['zonaId'];
    $tipoRuta = $_GET['tipoRuta'];
    $tipoVendedorRuta = $_GET['tipoVendedorRuta'];//1 Principal - 2 Ayudante
    $empleados = $modelEmpleado->obtenerEmpleadosZonaTipoRuta($zonaId, $tipoRuta,$tipoVendedorRuta);
    
    echo json_encode($empleados);
?>