<?php
	include('../../view/session1.php');
	include('../../model/ModelDescuentoDeposito.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelDescuentoDeposito = new ModelDescuentoDeposito();
	date_default_timezone_set('America/Mexico_City');

	/*Obtenemos los datos*/
	$fecha = ($_POST["fecha"]) ? $_POST["fecha"]: date('Y-m-d');
	$zonaId = floatval($_POST["zonaId"]);
    $pagoElectronico = floatval($_POST["pagoElectronico"]);
	$valeRetiro = floatval($_POST["valeRetiro"]);
	$descripcionValeRetiro = $_POST["descripcionValeRetiro"];
	$gastos = floatval($_POST["gastos"]);
	$cheque = floatval($_POST["cheque"]);
	$otrasSalidas = floatval($_POST["otrasSalidas"]);
  
	$total = $pagoElectronico + $valeRetiro + $gastos + $cheque + $otrasSalidas;

	// Validar si se ha subido un comprobante
	if (isset($_FILES['comprobante'])) {
	    if ($_FILES['comprobante']['error'] === 0) {
	        $nombreArchivo = $_FILES['comprobante']['name'];
	        $rutaTemporal = $_FILES['comprobante']['tmp_name'];
	        $directorioDestino = '../../view/descuentosdeposito/comprobantes/';
	        $maxSize = 1 * 1024 * 1024; // 1 MB en bytes
	        $nuevoAncho = 800; // Redimensionar ancho máximo
	        $nuevoAlto = 600;  // Redimensionar alto máximo

	        // Verificar tamaño del archivo
	        if ($_FILES['comprobante']['size'] > $maxSize) {
	            // Redimensionar y optimizar imagen si es mayor de 1 MB
	            redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70); // Reducimos calidad al 70%

	            // Verificar de nuevo si después de la optimización el archivo sigue siendo mayor de 1 MB
	            if (filesize($rutaTemporal) > $maxSize) {
	                echo "<script> 
	                alert('El archivo sigue siendo mayor a 1 MB incluso después de optimizar.'); 
	                window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
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
	            $comprobanteCreditoGas = $rutaFinal; // Asigna la ruta del comprobante
	        } else {
	            echo "<script> 
	            alert('Error al mover el archivo.'); 
	            window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
	            </script>";
	            exit;
	        }
	    } else {
	        // Ver error de la subida
	        $error = $_FILES['comprobante']['error'];
	        echo "<script> 
	        alert('Error en la subida de archivo: Código de error $error'); 
	        window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
	        </script>";
	        exit;
	    }
	} else {
	    echo "<script> 
	    alert('Debes subir un comprobante antes de continuar'); 
	    window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
	    </script>";
	    exit;
	}

	if($total <= 0){
		echo "<script> 
	         alert('Ingresa las cantidades correctas'); 
			window.location.href = '../../view/index.php?action=descuentosdeposito/nuevo.php'; 
		  </script>";
	}
	else{
		$descuentoDeposito = $modelDescuentoDeposito->insertar($fecha, $zonaId, $pagoElectronico, $valeRetiro, $descripcionValeRetiro, $gastos, $cheque, $otrasSalidas, $comprobanteCreditoGas);

		echo "<script> 
	         alert('Detalle agregado correctamente'); 
			window.location.href = '../../view/index.php?action=descuentosdeposito/index.php&zona=".$zonaId."';
		  </script>";
	}

	// Función para redimensionar y optimizar imágenes solo si es mayor de 1 MB
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
?>
