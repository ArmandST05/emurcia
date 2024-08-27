<?php
include('../../model/ModelPresupuesto.php');
$modelPresupuesto = new ModelPresupuesto();

//Obtenemos los datos
$zonaId = $_POST["zonaId"];
$anio = $_POST["anio"];
$mes = $_POST["mes"];
$conceptoId = $_POST["conceptoId"];
$cantidad = $_POST["cantidad"];

if (strlen($zonaId) < 1 || strlen($anio) < 1 || strlen($mes) < 1 || strlen($conceptoId) < 1 || strlen($cantidad) < 1) {
	http_response_code(500);
} else {
	$presupuesto = $modelPresupuesto->insertarPorConcepto($zonaId,$anio,$mes,$conceptoId,$cantidad);
	if($presupuesto) return $presupuesto;
	return http_response_code(500);
}
