<?php
include('../../model/ModelReporte.php');

$model_rep = new ModelReporte();
$zona = $_POST["zone"];
$model_rep->crearpdffinmes($zona);
echo "<script> 
		alert('PDF generado');
		window.location.href = '../../view/creditos/gen_report.php';
	  </script>";


?>