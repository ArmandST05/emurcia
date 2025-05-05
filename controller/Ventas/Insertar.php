<?php
include('../../view/session1.php');
include('../../model/ModelVenta.php');
include('../../model/ModelInventario.php');
include('../../model/ModelClienteDescuento.php');
include('../../model/ModelRuta.php');
include('../../model/ModelEmpleado.php');
include('../../model/ModelAutoconsumo.php');

$modelVenta = new ModelVenta();
$modelInventario = new ModelInventario();
$modelClienteDescuento = new ModelClienteDescuento();
$modelRuta = new ModelRuta();
$modelEmpleado = new ModelEmpleado();
$modelAutoconsumo = new ModelAutoconsumo();

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

$clientesDescuento = $_POST["clientesDescuento"] ?? null;

if (strlen($rutaVta) < 1 || strlen($productoVta) < 1) {
    echo "<script> alert('Ingresa todos los datos por favor'); </script>";
} else {
    $ruta = $modelRuta->obtenerRutaId($rutaVta);
    $tipoRuta = $ruta["tipo_ruta_id"];

    $vendedor1Id = null;
    $vendedor2Id = null;

    if ($tipoRuta == 5) { // Estación
        $vendedor1Id = $_POST["vendedorSelect"] ?? null;
    } else {
        $vendedor1Id = $_POST["vendedor1Id"] ?? null;
        $vendedor2Id = $_POST["vendedor2Id"] ?? null;
    }

    $modelVenta->insertarVenta($fecha, $hora, $zonaId, $rutaVta);
    $ultimaVentaZona = $modelVenta->getUltimaVentaZona($fecha, $zonaId);
    $ultimaVenta = $ultimaVentaZona ? reset($ultimaVentaZona) : null;

    if ($ultimaVenta) {
        $modelVenta->insertarDetalleVenta(
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

        $detalleVentaZona = $modelVenta->getUltimoDetalleVenta($ultimaVenta['idventa']);
        $ultimoDetalleVenta = $detalleVentaZona ? reset($detalleVentaZona) : null;

        if ($ultimoDetalleVenta && !empty($rubros)) {
            foreach ($rubros as $key => $rubro) {
                if ($rubro > 0) {
                    $modelVenta->insertarRubrosDetalleVenta(
                        $ultimoDetalleVenta['iddetalleventa'],
                        $key,
                        $rubro
                    );
                }
            }
        }

        // Inventario
        if ($productoVta == 4) { // Litros
            if ($porcentajeInicialVta > $porcentajeFinalVta) {
                $salidaVta = $porcentajeInicialVta - $porcentajeFinalVta;
                $modelInventario->insertarSalidaInventario($fecha, $rutaVta, $zonaId, $productoVta, $salidaVta, $ultimoDetalleVenta['iddetalleventa']);
            } elseif ($porcentajeFinalVta > $porcentajeInicialVta) {
                $entradaVta = $porcentajeFinalVta - $porcentajeInicialVta;
                $modelInventario->insertarEntradaInventario($fecha, $rutaVta, $zonaId, $productoVta, $entradaVta, $ultimoDetalleVenta['iddetalleventa']);
            }
        } else { // Cilindros
            $salidaVta = $cantidadVta + $otrasSalidasVta + $pruebasVta + $consumoInternoVta + $traspasosVta;
            $modelInventario->insertarSalidaInventario($fecha, $rutaVta, $zonaId, $productoVta, $salidaVta, $ultimoDetalleVenta['iddetalleventa']);
            if ($entradasVta > 0) {
                $modelInventario->insertarEntradaInventario($fecha, $rutaVta, $zonaId, $productoVta, $entradasVta, $ultimoDetalleVenta['iddetalleventa']);
            }
        }

        // Descuentos a clientes
        if (!empty($clientesDescuento)) {
            foreach ($clientesDescuento as $key => $clienteDescuento) {
                $modelClienteDescuento->insertarDetalleVenta(
                    $ultimoDetalleVenta['iddetalleventa'],
                    $key,
                    $clienteDescuento['descuentoId'],
                    $clienteDescuento['cantidad'],
                    $clienteDescuento['total']
                );
            }
        }

        // Empleados
        if (!empty($vendedor1Id)) {
            $empleado1 = $modelEmpleado->obtenerEmpleadoPorId($vendedor1Id);
            if ($empleado1) {
                $modelVenta->insertarVentaEmpleado($ultimaVenta["idventa"], $vendedor1Id, $empleado1["tipo_empleado_id"], 0);
            }
        }
        if (!empty($vendedor2Id)) {
            $empleado2 = $modelEmpleado->obtenerEmpleadoPorId($vendedor2Id);
            if ($empleado2) {
                $modelVenta->insertarVentaEmpleado($ultimaVenta["idventa"], $vendedor2Id, $empleado2["tipo_empleado_id"], 1);
            }
        }

        // Autoconsumos
        if (!empty($_POST['rutasAutoconsumo'])) {
            $fechaInicio = $_POST['fecha_inicio'] ?? $fecha;
            $fechaFin = $_POST['fecha_fin'] ?? $fecha;

            foreach ($_POST['rutasAutoconsumo'] as $rutaId => $datos) {
				$litros = isset($datos['litros']) ? floatval($datos['litros']) : 0;
				$kmi = isset($datos['kmi']) ? intval($datos['kmi']) : 0;
				$kmf = isset($datos['kmf']) ? intval($datos['kmf']) : 0;
				$costo = isset($datos['costo']) ? floatval($datos['costo']) : 0;
				$comprobante = ''; // Puedes agregar lógica para obtener comprobantes si es necesario
			
				$modelAutoconsumo->insertar(
					$rutaId,
					'Gas LP',
					$litros,
					$costo,
					$kmi,
					$kmf,
					$fechaInicio,
					$fechaFin,
					$comprobante
				);
			}
			
        }
    }

    echo "<script>
        alert('Venta agregada exitosamente');
        window.location.href = '../../view/index.php?action=ventas/index.php&zona=" . $zonaId . "';
    </script>";
}
?>
