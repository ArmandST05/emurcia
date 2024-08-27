<?php
include('../../model/ModelPresupuestoConcepto.php');
$modelPresupuestoConcepto = new ModelPresupuestoConcepto();

//Obtenemos los datos
$tipoGastoId = $_POST["tipoGastoId"];
$zonaId = $_POST["zonaId"];
$rutaId = $_POST["rutaId"];
$anio = $_POST["anio"];
$mes = $_POST["mes"];

if (strlen($tipoGastoId) < 1 || strlen($zonaId) < 1 || strlen($anio) < 1 || strlen($mes) < 1) {
	http_response_code(500);
} else {
	if($rutaId) $presupuesto = $modelPresupuestoConcepto->eliminarZonaRutaAnioMes($tipoGastoId,$zonaId,$rutaId,$anio,$mes);
	else $presupuesto = $modelPresupuestoConcepto->eliminarZonaAnioMes($tipoGastoId,$zonaId,$anio,$mes);
	return $presupuesto;
}
?>