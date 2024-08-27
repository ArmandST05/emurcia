<?php
include('../../view/session1.php');
include('../../model/ModelVenta.php');
include('../../model/ModelInventario.php');
include('../../model/ModelClienteDescuento.php');
include('../../model/ModelRuta.php');
include('../../model/ModelEmpleado.php');
/* Variable para llamar método de Modelo */
$modelVenta = new ModelVenta();
$modelInventario = new ModelInventario();
$modelClienteDescuento = new ModelClienteDescuento();
$modelRuta = new ModelRuta();
$modelEmpleado = new ModelEmpleado();

date_default_timezone_set('America/Mexico_City');

$fecha = date("Y-m-d");
$hora = date("h:i");
$zonaId = $_POST["zonaId"];

$rutaVta = $_POST["rutaVta"];
$productoVta = $_POST["productoVta"];

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

$clientesDescuento = (isset($_POST["clientesDescuento"])) ? $_POST["clientesDescuento"] : null;

if (strlen($rutaVta) < 1 || strlen($productoVta) < 1) {
	echo "<script> 
				alert('Ingresa todos los datos por favor');
			</script>";
} else {

	//Buscar ruta y guardar vendedores de acuerdo al tipo
	$ruta = $modelRuta->obtenerRutaId($rutaVta);
	$tipoRuta = $ruta["tipo_ruta_id"];

	if ($tipoRuta == 5) { //Estación carburación
		if (isset($_POST["vendedorSelect"])) {
			$vendedor1Id = $_POST["vendedorSelect"];
			$vendedor2Id = null;
		} else {
			$vendedor1Id = null;
			$vendedor2Id = null;
		}
	} else {
		if (isset($_POST["vendedor1Id"])) {
			$vendedor1Id = $_POST["vendedor1Id"];
		} else $vendedor1Id = null;
		if (isset($_POST["vendedor2Id"])) {
			$vendedor2Id = $_POST["vendedor2Id"];
		} else $vendedor2Id = null;
	}

	$insertarVenta = $modelVenta->insertarVenta(
		$fecha,
		$hora,
		$zonaId,
		$rutaVta
	);

	$ultimaVenta = $modelVenta->getUltimaVentaZona($fecha, $zonaId);
	$ultimaVenta = reset($ultimaVenta);

	$insertarDetalleVenta = $modelVenta->insertarDetalleVenta(
		$ultimaVenta['idventa'],
		$productoVta,

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

	$ultimoDetalleVenta = $modelVenta->getUltimoDetalleVenta($ultimaVenta['idventa']);
	$ultimoDetalleVenta = reset($ultimoDetalleVenta);
	if (!empty($rubros)) {

		foreach ($rubros as $key => $rubro) {
			if ($rubro > 0) {
				$insertarRubros = $modelVenta->insertarRubrosDetalleVenta(
					$ultimoDetalleVenta['iddetalleventa'],
					$key, //RubroId
					$rubro //Cantidad
				);
			}
		}
	}
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
				$ultimoDetalleVenta['iddetalleventa']
			);
		} else if ($porcentajeFinalVta > $porcentajeInicialVta) {
			$entradasVta = $porcentajeFinalVta - $porcentajeInicialVta;
			$insertarEntradaInventario = $modelInventario->insertarEntradaInventario(
				$fecha,
				$rutaVta, //RutaId
				$zonaId,
				$productoVta, //ProductoId
				$entradasVta, //Cantidad
				$ultimoDetalleVenta['iddetalleventa']
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
			$ultimoDetalleVenta['iddetalleventa']
		);

		if ($entradasVta > 0) {
			$insertarEntradaInventario = $modelInventario->insertarEntradaInventario(
				$fecha,
				$rutaVta, //RutaId
				$zonaId,
				$productoVta, //ProductoId
				$entradasVta, //Cantidad
				$ultimoDetalleVenta['iddetalleventa']
			);
		}
	}

	//Guardar clientes de descuento en caso de ser necesario
	if (!empty($clientesDescuento)) {
		foreach ($clientesDescuento as $key => $clienteDescuento) {

			$insertarClienteDescuentoVenta = $modelClienteDescuento->insertarDetalleVenta(
				$ultimoDetalleVenta['iddetalleventa'],
				$key, //Id del cliente
				$clienteDescuento['descuentoId'],
				$clienteDescuento['cantidad'],
				$clienteDescuento['total']
			);
		}
	}
	if(isset($vendedor1Id)){
		$empleado1 = $modelEmpleado->obtenerEmpleadoPorId($vendedor1Id);
		$modelVenta->insertarVentaEmpleado($ultimaVenta["idventa"], $vendedor1Id,$empleado1["tipo_empleado_id"],0);
	}
	if(isset($vendedor2Id)){
		$empleado2 = $modelEmpleado->obtenerEmpleadoPorId($vendedor2Id);
		$modelVenta->insertarVentaEmpleado($ultimaVenta["idventa"], $vendedor2Id,$empleado2["tipo_empleado_id"],1);
	}

	echo "<script>
		alert('Venta agregada exitosamente');
		window.location.href = '../../view/index.php?action=ventas/index.php&zona=" . $zonaId . "';
	</script>";
}
