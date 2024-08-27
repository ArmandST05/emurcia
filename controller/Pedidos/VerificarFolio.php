<?php
	include('../../model/ModelPedido.php');	
	$modelPedido = new ModelPedido();
	
    $folioNota = $_POST['folioNota'];

    $folios = $modelPedido->verificarFolio($folioNota);
    $folios = reset($folios);
    $coincidenciasFolio = $folios["folios"];

    if($coincidenciasFolio == 0) echo "No";
    else echo "Si";
?>