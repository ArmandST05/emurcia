<?php
include('../../model/ModelInventario.php');
/* Variable para llamar método de Modelo */
$modelInventario = new ModelInventario();
date_default_timezone_set('America/Mexico_City');
$fecha = date("Y-m-d");
$rutaId = $_GET["rutaId"];
$productoId = $_GET["productoId"];

$entradasInventario = $modelInventario->obtenerEntradasRutaProducto($rutaId,$productoId);
$entradasInventario = reset($entradasInventario);
$entradasInventario = floatval($entradasInventario["cantidad"]);

$salidasInventario = $modelInventario->obtenerSalidasRutaProducto($rutaId,$productoId);
$salidasInventario = reset($salidasInventario);
$salidasInventario = floatval($salidasInventario["cantidad"]);

if($entradasInventario > 0 && $salidasInventario > 0) $inventarioActual = $entradasInventario - $salidasInventario;
else $inventarioActual = $entradasInventario;

echo json_encode($inventarioActual);
?>