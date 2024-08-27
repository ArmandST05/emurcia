<?php
include('../../model/ModelCredito.php');
$modelCredito = new ModelCredito();

$id = $_POST["id"];
$datosCredito = $modelCredito->obtenerCreditoGasolinaId($id);
$datosCredito = reset($datosCredito);

$id_credito = $datosCredito["idcreditogasolina"];
$id_cliente = $datosCredito["id_cliente"];
$factura = $datosCredito["num_factura"];
$tipo = $datosCredito["tipo"];
$impor = $datosCredito["importe"];
$zona = $datosCredito["zona_id"];
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
		//$modelCredito->delcredgasolina($id_credito,$id_cliente);	//Borramos el credito
		$modelCredito->updatecredit($new_disp,$id_cliente,$new_used); //Actualizamos el credito de la tabla cliente [usado y disponible]
		$modelCredito->actualizarotorgadogasolina($id_cliente,$factura,$zona); // Ponemos Disponible el otorgado linkeado a este recuperado eliminado
		$modelCredito->eliminarAbonosRecuperado($id_cliente,$factura,$zona);
		
		/*echo "<script> 
			alert('Crédito recuperado eliminado. El crédito otorgado está disponible para recuperarse');
			window.location.href = '../../view/creditosgasolina/index_creditos.php'; 
		  </script>";*/
	}else if($tipo == 0){//Otorgado
		$modelCredito->delcredgasolina($id_credito,$id_cliente);	//Borramos el credito
		$modelCredito->updatecredit($new_disp,$id_cliente,$new_used); //Actualizamos el crédito de la tabla cliente [usado y disponible]
		$modelCredito->eliminarrecuperadogasolinna($id_cliente,$factura,$zona);
		$modelCredito->eliminarAbonosRecuperado($id_cliente,$factura,$zona);
		/*echo "<script> 
			alert('Credito otorgado eliminado [Si existía un crédito recuperado de esta nota, fue eliminada]');
			window.location.href = '../../view/creditosgasolina/index_creditos.php'; 
		  </script>";*/
	}
}
?>