<?php
include('../../view/session1.php');
include('../../model/ModelVenta.php');
include('../../model/ModelInventario.php');
include('../../model/ModelClienteDescuento.php');
/* Variable para llamar método de Modelo */
$modelVenta = new ModelVenta();
$modelInventario = new ModelInventario();
$modelClienteDescuento = new ModelClienteDescuento();

date_default_timezone_set('America/Mexico_City');
if ($_SESSION["tipoUsuario"] == "su") $fecha = $_POST["fechaVta"];
else $fecha = date("Y-m-d");

$detalleVtaId = $_POST["detalleVtaId"];
$ventaId = $_POST["ventaId"];

$rutaVta = $_POST["rutaId"];
$zonaId = $_SESSION["zonaId"];
$productoVta = $_POST["productoId"];

$lecturaInicialVta = $_POST["lecturaInicialVta"];
$lecturaFinalVta = $_POST["lecturaFinalVta"];
$porcentajeInicialVta = $_POST["porcentajeInicialVta"];
$porcentajeFinalVta = $_POST["porcentajeFinalVta"];

$cantidadVta = $_POST["cantidadVta"];
$precioVta = $_POST["precioVta"];

$cantidadLPVtaContado = $_POST["cantidadLPVtaContado"];
$descuentoTotalVtaContado = $_POST["descuentoTotalVtaContado"];

$cantidadLPVtaCredito = $_POST["cantidadLPVtaCredito"];
$descuentoTotalVtaCredito = $_POST["descuentoTotalVtaCredito"];

$totalVta = $_POST["totalVta"];
$totalVtaCredito = $_POST["totalVtaCredito"];
$totalVtaContado = $_POST["totalVtaContado"];

$rubros = $_POST["rubros"];
$totalRubrosVta = $_POST["totalRubrosVta"];

$entradasVta = $_POST["entradasVta"];
$otrasSalidasVta = $_POST["otrasSalidasVta"];
$pruebasVta = $_POST["pruebasVta"];
$consumoInternoVta = $_POST["consumoInternoVta"];
$traspasosVta = $_POST["traspasosVta"];

if(isset($_POST["vendedorSelect"])){
	$vendedor1Id = $_POST["vendedorSelect"];
}else $vendedor1Id=null;

if(isset($_POST["ayudanteSelect"])){
	$vendedor2Id = $_POST["ayudanteSelect"];
}else $vendedor2Id=null;


$clientesDescuento = (isset($_POST["clientesDescuento"]))? $_POST["clientesDescuento"]: null;

if (strlen($detalleVtaId) < 1 || strlen($rutaVta) < 1 || strlen($productoVta) < 1) {
	echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=ventas/editar.php&id=" . $ventaId . "';
			</script>";
} else {
	$actualizarVenta = $modelVenta->actualizarFechaVenta($ventaId, $fecha);

	$modelVenta->eliminarVentaEmpleados($ventaId);
	$ventaEmpleados = $modelVenta->insertarVentaEmpleadoTodos($ventaId,$vendedor1Id, $vendedor2Id);

	if ($actualizarVenta) {

		$actualizarDetalleVenta = $modelVenta->actualizarDetalleVenta(
			$detalleVtaId,

			$lecturaInicialVta,
			$lecturaFinalVta,
			$porcentajeInicialVta,
			$porcentajeFinalVta,

			$cantidadVta,
			$precioVta,

			$cantidadLPVtaContado,
			$descuentoTotalVtaContado,
			$cantidadLPVtaCredito,
			$descuentoTotalVtaCredito,

			$totalVta,
			$totalVtaCredito,
			$totalVtaContado,

			$totalRubrosVta,

			$entradasVta,
			$otrasSalidasVta,
			$pruebasVta,
			$consumoInternoVta,
			$traspasosVta
		);

		$eliminarRubros = $modelVenta->eliminarRubrosDetalleVenta($detalleVtaId);
		if ($eliminarRubros) {
			if (!empty($rubros)) {
				foreach ($rubros as $key => $rubro) {
					if ($rubro > 0) {
						$insertarRubros = $modelVenta->insertarRubrosDetalleVenta(
							$detalleVtaId,
							$key, //RubroId
							$rubro //Cantidad
						);
					}
				}
			}

			$eliminarInventario = $modelInventario->eliminarInventarioDetalleVenta($detalleVtaId);
			if ($eliminarInventario) {
				//Venta de litros
				if ($productoVta == 4) {
					if ($porcentajeInicialVta > $porcentajeFinalVta) {
						$salidaVta = $porcentajeInicialVta - $porcentajeFinalVta;
						$insertarSalidaInventario = $modelInventario->insertarSalidaInventario(
							$fecha,
							$rutaVta, //RutaId
							$zonaId,
							$productoVta, //ProductoId
							$salidaVta, //Cantidad
							$detalleVtaId
						);
					} else if ($porcentajeFinalVta > $porcentajeInicialVta) {
						$entradasVta = $porcentajeFinalVta - $porcentajeInicialVta;
						$insertarEntradaInventario = $modelInventario->insertarEntradaInventario(
							$fecha,
							$rutaVta, //RutaId
							$zonaId,
							$productoVta, //ProductoId
							$entradasVta, //Cantidad
							$detalleVtaId
						);
					}
				} else {
					//Venta de cilindros
					$salidaVta = $cantidadVta + $otrasSalidasVta + $pruebasVta + $consumoInternoVta + $traspasosVta;
					$insertarSalidaInventario = $modelInventario->insertarSalidaInventario(
						$fecha,
						$rutaVta, //RutaId
						$zonaId,
						$productoVta, //ProductoId
						$salidaVta, //Cantidad
						$detalleVtaId
					);

					if ($entradasVta > 0) {
						$insertarEntradaInventario = $modelInventario->insertarEntradaInventario(
							$fecha,
							$rutaVta, //RutaId
							$zonaId,
							$productoVta, //ProductoId
							$entradasVta, //Cantidad
							$detalleVtaId
						);
					}
				}

				//Eliminar y guardar clientes de descuento en caso de ser necesario
				$eliminarClientesDescuento = $modelClienteDescuento->eliminarPorDetalleVenta($detalleVtaId);
				if (!empty($clientesDescuento)) {
					foreach ($clientesDescuento as $key => $clienteDescuento) {
						$insertarClienteDescuentoVenta = $modelClienteDescuento->insertarDetalleVenta(
							$detalleVtaId,
							$key, //Id del cliente
							$clienteDescuento['descuentoId'],
							$clienteDescuento['cantidad'],
							$clienteDescuento['total']
						);
					}
				}

				echo "<script>
					alert('Venta actualizada exitosamente');
					window.location.href = '../../view/index.php?action=ventas/index.php';
				</script>";
			} else {
				echo "<script>
					alert('El inventario no se actualizó correctamente. Intenta nuevamente.');
					window.location.href = '../../view/index.php?action=ventas/editar.php&id=" . $ventaId . "';
				</script>";
			}
		} else {
			echo "<script>
				alert('Los rubros no se actualizaron correctamente. Intenta nuevamente.');
				window.location.href = '../../view/index.php?action=ventas/editar.php&id=" . $ventaId . "';
			</script>";
		}
	} else {
		//No se actualizó la venta
		echo "<script>
			alert('La venta no se actualizó correctamente. Intenta nuevamente.');
			window.location.href = '../../view/index.php?action=ventas/editar.php&id=" . $ventaId . "';
		</script>";
	}
}
