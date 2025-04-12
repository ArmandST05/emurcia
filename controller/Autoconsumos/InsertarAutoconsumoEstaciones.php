<?php
include('../../model/ModelAutoconsumo.php');    
/*variable para llamar metodo de Modelo*/
$modelAutoconsumo = new ModelAutoconsumo();
date_default_timezone_set('America/Mexico_City');





$rutaId = $_POST["ruta"];
$litros = $_POST["litros"];
$costo_litro = $_POST["costo_litro"];
$fecha = $_POST["fecha"];

if (isset($_FILES['comprobante'])) {
    if ($_FILES['comprobante']['error'] === 0) {
        $nombreArchivo = $_FILES['comprobante']['name'];
        $rutaTemporal = $_FILES['comprobante']['tmp_name'];
        $directorioDestino = '../../view/autoconsumos/comprobantes_estaciones/';
        $maxSize = 1 * 1024 * 1024; // 5 MB en bytes
        $nuevoAncho = 800; // Redimensionar ancho máximo
        $nuevoAlto = 600;  // Redimensionar alto máximo

        // Verificar tamaño del archivo
        if ($_FILES['comprobante']['size'] > $maxSize) {
            // Redimensionar y optimizar imagen si es mayor de 5 MB
            redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70); // Reducimos calidad al 70%

            // Verificar de nuevo si después de la optimización el archivo sigue siendo mayor de 5 MB
            if (filesize($rutaTemporal) > $maxSize) {
                echo "El archivo sigue siendo mayor a 5 MB incluso después de optimizar.";
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
            $comprobante = $rutaFinal;
        } else {
            echo "Error al mover el archivo.";
            exit;
        }
    } else {
        echo "Error en la subida del archivo.";
        exit;
    }
} else {
    echo "No se envió ningún archivo.";
    exit;
}

// Llamar al método para insertar en la base de datos
$autoconsumoEstacion = $modelAutoconsumo->insertar_AutoconcumoEstaciones($rutaId, $litros, $costo_litro, $fecha, $comprobante);

if ($autoconsumoEstacion) {
    echo "<script> 
    alert('Autoconsumo agregado correctamente'); 
    window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
</script>";
}
else {
echo "<script> 
    alert('Ha ocurrido un error al agregar el autoconsumo'); 
    window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
</script>";
}

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
?>
