<?php
include('../../view/session1.php');
include('../../model/ModelVenta.php');
include('../../model/ModelInventario.php');

/* Variable para llamar método de Modelo */
$modelVenta = new ModelVenta();
$modelInventario = new ModelInventario();

date_default_timezone_set('America/Mexico_City');

$fecha = date("Y-m-d");
$rutaId = $_POST["rutaId"];
$zonaId = $_SESSION["zonaId"];
$productoId = $_POST["productoId"];
$nuevoInventario = floatval($_POST["inventarioNuevo"]);

$entradasInventario = $modelInventario->obtenerEntradasRutaProducto($rutaId,$productoId);
$entradasInventario = reset($entradasInventario);
$entradasInventario = floatval($entradasInventario["cantidad"]);

$salidasInventario = $modelInventario->obtenerSalidasRutaProducto($rutaId,$productoId);
$salidasInventario = reset($salidasInventario);
$salidasInventario = floatval($salidasInventario["cantidad"]);

if($entradasInventario > 0 && $salidasInventario > 0){
	$inventarioActual = $entradasInventario - $salidasInventario;
}
else{
	$inventarioActual = $entradasInventario;
}

if($inventarioActual > $nuevoInventario){
	$cantidad = $inventarioActual - $nuevoInventario;

	$insertarSalidaInventario = $modelInventario->insertarSalidaInventario(
		$fecha,
		$rutaId, //RutaId
		$zonaId,
		$productoId, //ProductoId
		$cantidad //Cantidad
	);
}
else if($inventarioActual < $nuevoInventario){
	$cantidad = $nuevoInventario - $inventarioActual;

	$insertarEntradaInventario = $modelInventario->insertarEntradaInventario(
		$fecha,
		$rutaId, //RutaId,
		$zonaId,
		$productoId, //ProductoId
		$cantidad //Cantidad
	);
}
	echo "<script>
		alert('Inventario actualizado');
		window.location.href = '../../view/index.php?action=inventario/index_rutas.php';
	</script>";
?>