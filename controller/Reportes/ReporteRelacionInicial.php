<?php
include('../../model/ModelReporte.php');

$model_rep = new ModelReporte();
$zona = $_POST["zone"];
$mes = $_POST["mes"];
$anio = $_POST["anio"];
$saldo =$_POST["saldot"];
$model_rep->crearpdfrelacioninicial($zona,$mes,$anio,$saldo);

echo "<script> 
		alert('PDF generado');
		window.location.href = '../../view/creditos/index_creditos.php';
	  </script>";


?>