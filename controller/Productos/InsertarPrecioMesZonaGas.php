<?php
include('../../model/ModelProducto.php');
include('../../model/ModelPrecioProducto.php');
include('../../model/ModelZona.php');
$modelProducto = new ModelProducto();
$modelPrecioProducto = new ModelPrecioProducto();
$modelZona = new ModelZona();
date_default_timezone_set('America/Mexico_City');

//Obtenemos los datos
$precioKilo = $_POST["precio_kilo"];
$precioLitro = $_POST["precio_litro"];

$anio = date("Y");
$zonaId = $_POST["zona"];
$rutaId = (isset($_POST["ruta"])) ? $_POST["ruta"]: 0;
$dia = date("m");
//Se requiren el valor numérico del mes
$mes = date("n");
$productos = $_POST["productos"];//Son los precios de productos (Cilindros)

//Validamos los no nulos
if (strlen($precioLitro) < 1 || strlen($precioKilo) < 1) {
	echo "<script> 
				alert('Asigna un precio.');
				window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gas.php';
			  </script>";
} else {
		if (strlen($zonaId) < 1) {
			echo "<script> 
						alert('Asigna la zona.');
						window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gas.php';
					</script>";
		} else {
			$modelPrecioProducto->insertarPrecioGasMesZona($mes,$anio,$zonaId,$rutaId,$precioKilo,$precioLitro);

			//Guardar los precios de los productos (Cilindros)
			if (!empty($productos)) {
				foreach ($productos as $id => $precio) {
					$insertarPrecio = $modelProducto->insertarPrecioProductoZona(
						$zonaId,
						$rutaId,
						$mes,
						$anio,
						$id,//productoId
						floatval($precio)
					);
				}
			}

			echo "<script> 
					window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gas.php';
				</script>";
		}
}
