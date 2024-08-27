<?php
include('../../model/ModelPresupuestoConcepto.php');
$modelPresupuestoConcepto = new ModelPresupuestoConcepto();

//Obtenemos los datos
$zonaId = $_POST["zonaId"];
$rutaId = $_POST["rutaId"];
$anio = $_POST["anio"];
$mes = $_POST["mes"];
$conceptoId = $_POST["conceptoId"];

var_dump($conceptoId);
if (strlen($zonaId) < 1 || strlen($anio) < 1 || strlen($mes) < 1 || strlen($conceptoId) < 1) {
	http_response_code(500);
} else {
	$presupuesto = $modelPresupuestoConcepto->insertarPorConcepto($zonaId,$anio,$mes,$conceptoId,$rutaId);
	if($presupuesto) return $presupuesto;
	return http_response_code(500);
}
