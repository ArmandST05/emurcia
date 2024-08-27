<?php
include('../../model/ModelReporte.php');
include('../../model/ModelZona.php');
$modelReporte = new ModelReporte();
$modelZona = new ModelZona();

$zonaId = $_POST["zona"];
$zona = $modelZona->obtenerZonaId($zonaId);//Buscar nombre de la zona para mostrar en reporte
$zona = $zona["nombre"];
$fecha = $_POST["fecha"];

$modelReporte->crearPdfInventarioZonaFecha($zonaId,$zona,$fecha);
?>