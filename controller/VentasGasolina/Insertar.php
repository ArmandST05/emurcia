<?php
include('../../view/session1.php');
include('../../model/ModelVentaGasolina.php');
include('../../model/ModelInventario.php');
/* Variable para llamar método de Modelo */
$modelVentaGasolina = new ModelVentaGasolina();
$modelInventario = new ModelInventario();

date_default_timezone_set('America/Mexico_City');

$fecha = $_POST["fechaVta"];
$hora = date("h:i");
$zonaId = $_SESSION["zonaId"];

$rutaVta = $_POST["rutaVta"];
$productoVta = $_POST["productoVta"];

$cantidadVta = $_POST["cantidadVta"];
$cantidadVtaCredito = $_POST["cantidadVtaCredito"];
$precioVta = $_POST["precioVta"];

$totalVta = $_POST["totalVta"];
$totalVtaCredito = $_POST["totalVtaCredito"];
$totalVtaContado = $_POST["totalVtaContado"];

$pruebasVta = $_POST["pruebasVta"];

if (strlen($rutaVta) < 1 || strlen($productoVta) < 1 || strlen($cantidadVta) < 1 || strlen($precioVta) < 1) {
	echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=ventasgasolina/nuevo.php';
			</script>";
} else {
	$ventaId = $modelVentaGasolina->insertarVenta(
		$fecha,
		$hora,
		$zonaId,
		$rutaVta
	);
	if ($ventaId) {
		$detalleVentaId = $modelVentaGasolina->insertarDetalleVenta(
			$ventaId,
			$productoVta,

			$cantidadVta,
			$cantidadVtaCredito,
			$precioVta,

			$totalVta,
			$totalVtaCredito,
			$totalVtaContado,

			$pruebasVta
		);
	} else {
		echo "<script> 
			alert('Ocurrió un error al registrar la venta');
			window.location.href = '../../view/index.php?action=ventasgasolina/nuevo.php';
		</script>";
	}
	if ($detalleVentaId) {
		//Venta de litros
		if ($pruebasVta) {
			$salidaVta = $pruebasVta;
			$insertarSalidaInventario = $modelInventario->insertarSalidaInventario(
				$fecha,
				$rutaVta, //RutaId
				$zonaId, //ZonaId
				$productoVta, //ProductoId
				$salidaVta, //Cantidad
				$detalleVentaId
			);
		}
		echo "<script>
			alert('Venta agregada exitosamente');
			window.location.href = '../../view/index.php?action=ventasgasolina/index.php';
		</script>";
	} else {
		echo "<script> 
			alert('Ocurrió un error al registrar la venta');
			window.location.href = '../../view/index.php?action=ventasgasolina/nuevo.php';
		</script>";
	}
}
