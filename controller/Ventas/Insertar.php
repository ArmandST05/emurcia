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
	if (isset($_FILES['comprobante_venta'])) {
        if ($_FILES['comprobante_venta']['error'] === 0) {
            $nombreArchivo = $_FILES['comprobante_venta']['name'];
            $rutaTemporal = $_FILES['comprobante_venta']['tmp_name'];
            $directorioDestino = '../../view/ventas/comprobantes/';
            $maxSize = 1 * 1024 * 1024; // 1 MB en bytes
            $nuevoAncho = 800; // Redimensionar ancho máximo
            $nuevoAlto = 600;  // Redimensionar alto máximo

            // Verificar tamaño del archivo
            if ($_FILES['comprobante_venta']['size'] > $maxSize) {
                // Redimensionar y optimizar imagen si es mayor a 1 MB
                redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70); // Reducimos calidad al 70%

                // Verificar de nuevo si después de la optimización el archivo sigue siendo mayor de 1 MB
                if (filesize($rutaTemporal) > $maxSize) {
                    echo "<script> 
                    alert('El archivo sigue siendo mayor a 1 MB incluso después de optimizar.'); 
                    window.location.href = '../../view/index.php?action=ventas/nuevo.php'; 
                    </script>";
                    exit;
                }
            }

            // Crear directorio si no existe
            if (!file_exists($directorioDestino)) {
                mkdir($directorioDestino, 0777, true);
            }

            // Mover el archivo al destino final
            $rutaFinal = $directorioDestino . basename($nombreArchivo);
            if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
                $comprobanteVenta = $rutaFinal;
            } else {
                echo "<script> 
                alert('Error al mover el archivo.'); 
                window.location.href = '../../view/index.php?action=ventas/index.php'; 
                </script>";
                exit;
            }
        } else {
            // Ver error de la subida
            $error = $_FILES['comprobante_venta']['error'];
            echo "<script> 
            alert('Error en la subida de archivo: Código de error $error'); 
            window.location.href = '../../view/index.php?action=ventas/index.php'; 
            </script>";
            exit;
        }
    } else {
        echo "<script> 
        alert('No se envió ningún archivo.'); 
        window.location.href = '../../view/index.php?action=ventas/index.php'; 
        </script>";
        exit;
    }
    
    // Llamar al método para insertar en la base de datos
    $insertarVenta = $modelVenta->insertarVenta($fecha, $hora, $zonaId, $rutaVta, $comprobanteVenta);

    if($insertarVenta) {
        echo "<script> 
            alert('Venta agregada correctamente'); 
            window.location.href = '../../view/index.php?action=ventas/index.php'; 
        </script>";
    } else {
        echo "<script> 
            alert('Ha ocurrido un error al agregar la venta'); 
            window.location.href = '../../view/index.php?action=ventas/index.php'; 
        </script>";
    }

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
		$rutaVta,
		$comprobanteVenta
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

// Función para redimensionar y optimizar imágenes
function redimensionarYOptimizarImagen($rutaOrigen, $rutaDestino, $nuevoAncho, $nuevoAlto, $calidad) {
    // Obtener las dimensiones y el tipo de imagen original
    list($ancho, $alto, $tipoImagen) = getimagesize($rutaOrigen);

    // Calcular las proporciones de la nueva imagen
    $ratioOriginal = $ancho / $alto;
    $ratioNuevo = $nuevoAncho / $nuevoAlto;

    if ($ratioNuevo > $ratioOriginal) {
        // Mantener la proporción del ancho
        $nuevoAlto = $nuevoAncho / $ratioOriginal;
    } else {
        // Mantener la proporción del alto
        $nuevoAncho = $nuevoAlto * $ratioOriginal;
    }

    // Crear una imagen desde el archivo original según el tipo de imagen
    switch ($tipoImagen) {
        case IMAGETYPE_JPEG:
            $imagenOriginal = imagecreatefromjpeg($rutaOrigen);
            break;
        case IMAGETYPE_PNG:
            $imagenOriginal = imagecreatefrompng($rutaOrigen);
            break;
        default:
            // Si el tipo de imagen no es JPEG o PNG, no se puede redimensionar
            return false;
    }

    // Crear una nueva imagen vacía con las nuevas dimensiones
    $imagenRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    // Mantener la transparencia si es PNG
    if ($tipoImagen == IMAGETYPE_PNG) {
        imagealphablending($imagenRedimensionada, false);
        imagesavealpha($imagenRedimensionada, true);
    }

    // Redimensionar la imagen
    imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

    // Guardar la imagen redimensionada y optimizada en el destino
    if ($tipoImagen == IMAGETYPE_JPEG) {
        imagejpeg($imagenRedimensionada, $rutaDestino, $calidad); // El tercer parámetro es la calidad (0-100)
    } elseif ($tipoImagen == IMAGETYPE_PNG) {
        // En PNG, la calidad va de 0 (sin compresión) a 9 (máxima compresión)
        $calidadPng = ($calidad / 10); // Convertimos la calidad de un rango 0-100 a 0-9
        imagepng($imagenRedimensionada, $rutaDestino, $calidadPng);
    }

    // Liberar memoria
    imagedestroy($imagenOriginal);
    imagedestroy($imagenRedimensionada);

    return true;
}
}
