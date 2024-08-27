<?php
	include('../../view/session1.php');
	include('../../model/ModelPrecioProducto.php');
	$modelPrecioProducto = new ModelPrecioProducto();
	//Obtenemos los datos

	$precio = $_POST["price"];
	$ieps = $_POST["ieps"];
	if ($_SESSION["tipoUsuario"] == "su"){
		$zonaId = $_POST["zona"];
	}else{
		$zonaId = $_SESSION["zonaId"];
	}
	$fecha = $_POST["fecha"];

	$productoId = $_POST["producto"];

	//Validamos los no nulos
	if(strlen($precio) < 1 || strlen($ieps) < 1 || strlen($fecha) < 1 || strlen($zonaId) < 1 || strlen($productoId) < 1){
		echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gasolina.php';
			  </script>";
	}
	else{
		$nuevoPrecio = $modelPrecioProducto->insertarPrecioMesGasolinaProducto($fecha,$precio,$zonaId,$ieps,$productoId);
		if($nuevoPrecio){
			echo "<script> 
					alert('Precio capturado');
					window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gasolina.php';
				</script>";
		}
		else{
			echo "<script> 
				alert('Ha ocurrido un error al capturar precio');
				window.location.href = '../../view/index.php?action=preciosproductos/capturar_precio_mes_gasolina.php';
			</script>";
		}
	}
?>

 