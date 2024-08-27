<?php
include('../../view/session1.php');
include('../../model/ModelVentaGasolina.php');
$modelVentaGasolina = new ModelVentaGasolina();
include('../../model/ModelInventario.php');
$modelInventario = new ModelInventario();

date_default_timezone_set('America/Mexico_City');
$fecha = $_POST["fechaVta"];

$detalleVtaId = $_POST["detalleVtaId"];
$ventaId = $_POST["ventaId"];

$rutaVta = $_POST["rutaId"];
$zonaId = $_SESSION["zonaId"];
$productoVta = $_POST["productoId"];

$cantidadVta = $_POST["cantidadVta"];
$cantidadVtaCredito = $_POST["cantidadVtaCredito"];
$precioVta = $_POST["precioVta"];

$totalVta = $_POST["totalVta"];
$totalVtaCredito = $_POST["totalVtaCredito"];
$totalVtaContado = $_POST["totalVtaContado"];

$pruebasVta = $_POST["pruebasVta"];

if (strlen($detalleVtaId) < 1 || strlen($rutaVta) < 1 || strlen($productoVta) < 1 || strlen($cantidadVta) < 1 || strlen($precioVta) < 1) {
	echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=ventasgasolina/editar.php&id=" . $ventaId . "';
			</script>";
} else {
	$actualizarVenta = $modelVentaGasolina->actualizarFechaVenta($ventaId, $fecha);
	if ($actualizarVenta) {

		$actualizarDetalleVenta = $modelVentaGasolina->actualizarDetalleVenta(
			$detalleVtaId,

			$cantidadVta,
			$cantidadVtaCredito,
			$precioVta,

			$totalVta,
			$totalVtaCredito,
			$totalVtaContado,

			$pruebasVta
		);

		$eliminarInventario = $modelInventario->eliminarInventarioDetalleVenta($detalleVtaId);
		if ($eliminarInventario) {
			//Venta de litros
			if($pruebasVta){
				$salidaVta = $pruebasVta;
				$insertarSalidaInventario = $modelInventario->insertarSalidaInventario(
					$fecha,
					$rutaVta, //RutaId
					$zonaId,
					$productoVta, //ProductoId
					$salidaVta, //Cantidad
					$detalleVtaId
				);
			}	
		}
		echo "<script>
			alert('Venta actualizada exitosamente');
			window.location.href = '../../view/index.php?action=ventasgasolina/index.php';
		</script>";
	} else {
		//No se actualizó la venta
		echo "<script>
			alert('La venta no se actualizó correctamente. Intenta nuevamente.');
			window.location.href = '../../view/index.php?action=ventasgasolina/editar.php&id=" . $ventaId . "';
		</script>";
	}
}
