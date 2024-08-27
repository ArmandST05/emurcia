<?php
include('../../model/ModelInventario.php');
$modelInventario = new ModelInventario();
date_default_timezone_set('America/Mexico_City');

$zonaId = $_GET["zonaId"];
$rutaId = $_GET["rutaId"];
$fechaVta = $_GET["fechaVta"];
$fechaArray = explode("-",$fechaVta);

$mes = $fechaArray[1];
$anio = $fechaArray[0];

$fechaInicial = date($anio."-".$mes."-01");
$fechaFinal = date($anio."-".$mes."-31");

$inventarioDatos = $modelInventario->obtenerReporteInventarioGasolinaRutaFechas($fechaInicial, $fechaFinal,$mes,$anio,$zonaId,$rutaId);
$inventarioDatos = reset($inventarioDatos);
$inventarioContable = (floatval($inventarioDatos['inventario_inicial']) + floatval($inventarioDatos['total_compras']))-floatval($inventarioDatos["total_ventas"]);

echo json_encode($inventarioContable);
?>