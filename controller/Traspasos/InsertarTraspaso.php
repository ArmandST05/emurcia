<?php
	date_default_timezone_set('America/Mexico_City');
	include('../../view/session1.php');	
	include('../../model/ModelTraspaso.php');	

  	/* Variable para llamar método de Modelo */
	$modelTraspaso = new ModelTraspaso();
	date_default_timezone_set('America/Mexico_City');
	
	/* Obtenemos los datos */
	$fecha = date("Y-m-d");
	$zonaOrigen = $_SESSION["zonaId"];
    $zonaDestino = $_POST["zonaDestino"];
    $cantidad = $_POST["cantidad"];
	$comprobante = $_FILES['comprobante']; // Obtenemos el archivo comprobante

	/* Validaciones */
	if (empty($zonaOrigen) || empty($zonaDestino) || empty($cantidad)) {
		echo "<script> 
				alert('Ingresa todos los datos por favor');
				window.location.href = '../../view/index.php?action=traspasos/nuevo.php';
			  </script>";
		exit;
	}

	/* Procesamiento del archivo de comprobante */
	$comprobantePath = '';
	if (!empty($comprobante['name'])) {
		$uploadDir = '../../view/traspasos/comprobantes/'; // Carpeta donde se guardará el comprobante
		$comprobantePath = $uploadDir . basename($comprobante['name']);
		$comprobanteSize = $comprobante['size'];
		$comprobanteTmpPath = $comprobante['tmp_name'];
		$maxSize = 256 * 1024; // 256 KB

		// Verificamos si el archivo es mayor a 256 KB y lo optimizamos si es necesario
		if ($comprobanteSize > $maxSize) {
			// Obtener la información del archivo
			$imageInfo = getimagesize($comprobanteTmpPath);
			$mime = $imageInfo['mime'];

			// Optimizar según el tipo de imagen (JPEG o PNG)
			if ($mime == 'image/jpeg') {
				$sourceImage = imagecreatefromjpeg($comprobanteTmpPath);
				imagejpeg($sourceImage, $comprobantePath, 75); // Reducir calidad al 75%
				imagedestroy($sourceImage);
			} elseif ($mime == 'image/png') {
				$sourceImage = imagecreatefrompng($comprobanteTmpPath);
				imagepng($sourceImage, $comprobantePath, 6); // Reducir calidad de PNG (nivel 0-9)
				imagedestroy($sourceImage);
			} else {
				// Si no es JPEG o PNG, mover el archivo sin optimizar
				move_uploaded_file($comprobanteTmpPath, $comprobantePath);
			}
		} else {
			// Si no es necesario optimizar, movemos el archivo tal cual
			move_uploaded_file($comprobanteTmpPath, $comprobantePath);
		}
	}

	/* Insertar el traspaso */
	$nuevoTraspaso = $modelTraspaso->insertar($fecha, $zonaOrigen, $zonaDestino, $cantidad, $comprobantePath);
	if ($nuevoTraspaso) {
		echo "<script> 
				alert('Traspaso agregado'); 
				window.location.href = '../../view/index.php?action=traspasos/index.php'; 
			  </script>";
	} else {
		echo "<script> 
				alert('No se agregó el traspaso, intenta nuevamente'); 
				window.location.href = '../../view/index.php?action=traspasos/index.php'; 
			  </script>";
	}
?>
