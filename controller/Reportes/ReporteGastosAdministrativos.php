<?php
include('../../model/ModelReporte.php');
$modelReporte = new ModelReporte();

include('../../model/ModelZona.php');
$modelZona = new ModelZona();

$zonaId = $_POST["zona"];
$zona = $modelZona->obtenerZonaId($zonaId);
$zona = $zona["nombre"];

$mesInicial = $_POST["mesInicial"];
$anioInicial = $_POST["anioInicial"];
$mesFinal = $_POST["mesFinal"];
$anioFinal = $_POST["anioFinal"];

$modelReporte->crearPdfGastosAdministrativosZonaFechas($zonaId,$zona,$mesInicial,$anioInicial,$mesFinal,$anioFinal);
?>