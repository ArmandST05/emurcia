<?php
include('../../model/ModelInventario.php');
$modelInventario = new ModelInventario();

date_default_timezone_set('America/Mexico_City');

$fecha = date("Y-m-d");
$mes = date("n");
$anio = date("Y");

$rutaId = $_POST["rutaId"];
$zonaId = $_POST["zonaId"];
$productoId = $_POST["productoId"];
$cantidad = floatval($_POST["cantidad"]);

$inventario = $modelInventario->insertarInventarioInicial(
	$fecha,
	$mes,
	$anio,
	$rutaId,
	$zonaId,
	$productoId,
	$cantidad
);

if($inventario){
	echo "<script>
		window.location.href = '../../view/index.php?action=inventario/index_gasolina.php';
	</script>";
}
else{
	echo "<script>
		alert('Ha ocurrido un error al registrar el inventario inicial.');
		window.location.href = '../../view/index.php?action=inventario/index_gasolina.php';
	</script>";
}
?>