<?php
include('../../view/session1.php');
include('../../model/ModelDesviacionVolumen.php');
/* Variable para llamar método de Modelo */
$modelDesviacionVolumen = new ModelDesviacionVolumen();

date_default_timezone_set('America/Mexico_City');

$zonaId = $_SESSION["zonaId"];

if (strlen($_POST["rutaId"]) < 1 || strlen($_POST["productoId"]) < 1 || strlen($_POST["fecha"]) < 1) {
	echo "<script> 
				alert('Ingresa todos los datos por favor');	
				window.location.href = '../../view/index.php?action=desviacionvolumen/index.php';
			</script>";
} else {
	$validarRegistro = $modelDesviacionVolumen->validarPorRutaProductoFecha($_POST["rutaId"], $_POST["productoId"], $_POST["fecha"]);
	if ($validarRegistro) {
		echo "<script>
			alert('Ya existe un registro de desviación volumen para esta fecha.');
			window.location.href = '../../view/index.php?action=desviacionvolumen/index.php';
		</script>";
	} else {
		$insertarDesviacion = $modelDesviacionVolumen->insertar(
			$_POST["fecha"],
			$_SESSION["zonaId"],
			$_POST["rutaId"],
			$_POST["productoId"],
			$_POST["facturaRemision"],
			$_POST["volumenFactura"],
			$_POST["proveedorId"],
			$_POST["transporte"],
			$_POST["tanqueDescarga"],
			$_POST["volumenDescargaBruto"],
			$_POST["ventaDescarga"],
			$_POST["inventarioInicial"],
			$_POST["inventarioFinal"],
			$_POST["comprasDia"],
			$_POST["totalVendidoSistema"]//Ventas del día
		);
		echo "<script>
			alert('Registro agregado exitosamente');
			window.location.href = '../../view/index.php?action=desviacionvolumen/index.php';
		</script>";
	}
}
