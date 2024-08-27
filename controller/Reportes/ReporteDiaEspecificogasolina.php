<?php
include('../../model/ModelReporte.php');

$model_rep = new ModelReporte();
$dia = $_POST["dia"];
$mes = $_POST["mes"];
$anio = $_POST["anio"];
$zona = $_POST["zona"];

$model_rep->crearpdfdiaespecificogasolina($dia,$mes,$anio,$zona);
echo "<script> 
		alert('PDF generado');
		window.location.href = '../../view/creditos/gen_report.php';
	  </script>";


?>