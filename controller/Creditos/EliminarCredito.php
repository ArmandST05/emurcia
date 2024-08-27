<?php
include('../../model/ModelCredito.php');
$modelCredito = new ModelCredito();

$id = $_POST["id"];
$datosCredito = reset($modelCredito->obtenerCreditoGasId($id));

$id_credito = $datosCredito["idcreditogas"];
$id_cliente = $datosCredito["id_cliente"];
$factura = $datosCredito["num_factura"];
$tipo = $datosCredito["tipo"];
$impor = $datosCredito["importe"];
$zona = $datosCredito["zona"];

$flag = false;
//Actualizamos el crédito (De la factura eliminada)

$datoscliente = $modelCredito->verificarId($id_cliente);
foreach ($datoscliente as $key) {
	$disponible = $key["credit_actual"];
	$usado = $key["credit_use"];
}

if($tipo == 0){//Otorgado
	$sumaabonosfactura = $modelCredito->obtenerabonosfactura($id_cliente,$factura,$zona);
	$importeconabonos = $impor - $sumaabonosfactura;
	$new_used = $usado - $importeconabonos;
	$new_disp = $disponible + $importeconabonos;
	$flag = true;

}else if($tipo == 1){//Recuperado
	$new_used = $usado + $impor;
	$new_disp = $disponible - $impor;
	$flag = true;
}

if($flag = true){
	if($tipo == 1){//Recuperado
		$modelCredito->delcred($id_credito,$id_cliente);// Borramos el credito
		$modelCredito->updatecredit($new_disp,$id_cliente,$new_used); //Actualizamos el credito de la tabla cliente [usado y disponible]
		$modelCredito->actualizarotorgado($id_cliente,$factura,$zona); // Ponemos Disponible el otorgado linkeado a este recuperado eliminado
		$modelCredito->eliminarAbonosRecuperado($id_cliente,$factura,$zona); 
	}else if($tipo == 0){//Otorgado
		$modelCredito->delcred($id_credito,$id_cliente);	//Borramos el crédito
		$modelCredito->updatecredit($new_disp,$id_cliente,$new_used); //Actualizamos el credito de la tabla cliente [usado y disponible]
		$modelCredito->eliminarrecuperado($id_cliente,$factura,$zona);
		$modelCredito->eliminarAbonosRecuperado($id_cliente,$factura,$zona);
	}
}
?>