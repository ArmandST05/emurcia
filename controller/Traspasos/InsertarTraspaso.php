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
$destinoEstacion = $_POST["destinoEstacion"];  // Obtenemos la estación de destino
$comprobante = $_FILES['comprobante_traspaso']; // Obtener el archivo comprobante

/* Validaciones */
if (empty($zonaOrigen) || empty($zonaDestino) || empty($cantidad) || empty($destinoEstacion)) {  // Agregamos validación para la estación
    echo "<script> 
            alert('Ingresa todos los datos por favor');
            window.location.href = '../../view/index.php?action=traspasos/nuevo.php';
          </script>";
    exit;
}

/* Procesamiento del archivo de comprobante */
$comprobantePath = '';
if (!empty($_FILES['comprobante_traspaso']['name'])) { // Cambiar 'comprobante' a 'comprobante_traspaso'
    $comprobante = $_FILES['comprobante_traspaso']; // Obtener el archivo comprobante
    $uploadDir = '../../view/traspasos/comprobantes/'; // Carpeta donde se guardará el comprobante
    $comprobanteTmpPath = $comprobante['tmp_name'];
    $nombreArchivo = basename($comprobante['name']);
    $comprobantePath = $uploadDir . $nombreArchivo;
    $maxSize = 256 * 1024; // 256 KB
    $nuevoAncho = 800; // Ancho máximo para redimensionar
    $nuevoAlto = 600;  // Alto máximo para redimensionar

    // Verificar tamaño del archivo
    if ($comprobante['size'] > $maxSize) {
        // Redimensionar y optimizar si es mayor de 256 KB
        redimensionarYOptimizarImagen($comprobanteTmpPath, $comprobanteTmpPath, $nuevoAncho, $nuevoAlto, 70);

        // Verificar si sigue siendo mayor a 256 KB
        if (filesize($comprobanteTmpPath) > $maxSize) {
            echo "<script> 
            alert('El archivo sigue siendo mayor a 256 KB incluso después de optimizar.'); 
            window.location.href = '../../view/index.php?action=traspasos/nuevo.php'; 
            </script>";
            exit;
        }
    }

    // Crear directorio si no existe
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Mover el archivo optimizado al destino final
    if (move_uploaded_file($comprobanteTmpPath, $comprobantePath)) {
        // Comprobante guardado correctamente
    } else {
        echo "<script> 
            alert('Error al mover el archivo.'); 
            window.location.href = '../../view/index.php?action=traspasos/index.php'; 
            </script>";
        exit;
    }
}

/* Insertar el traspaso (agregamos la estación de destino en la inserción) */
$nuevoTraspaso = $modelTraspaso->insertar($fecha, $zonaOrigen, $zonaDestino, $cantidad, $destinoEstacion, $comprobantePath);
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

/* Función para redimensionar y optimizar imágenes */
function redimensionarYOptimizarImagen($rutaOrigen, $rutaDestino, $nuevoAncho, $nuevoAlto, $calidad) {
    list($ancho, $alto, $tipoImagen) = getimagesize($rutaOrigen);

    // Calcular proporciones
    $ratioOriginal = $ancho / $alto;
    $ratioNuevo = $nuevoAncho / $nuevoAlto;

    if ($ratioNuevo > $ratioOriginal) {
        $nuevoAlto = $nuevoAncho / $ratioOriginal;
    } else {
        $nuevoAncho = $nuevoAlto * $ratioOriginal;
    }

    // Crear imagen según el tipo
    switch ($tipoImagen) {
        case IMAGETYPE_JPEG:
            $imagenOriginal = imagecreatefromjpeg($rutaOrigen);
            break;
        case IMAGETYPE_PNG:
            $imagenOriginal = imagecreatefrompng($rutaOrigen);
            break;
        default:
            return false;
    }

    $imagenRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    // Mantener transparencia si es PNG
    if ($tipoImagen == IMAGETYPE_PNG) {
        imagealphablending($imagenRedimensionada, false);
        imagesavealpha($imagenRedimensionada, true);
    }

    // Redimensionar y guardar imagen
    imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

    if ($tipoImagen == IMAGETYPE_JPEG) {
        imagejpeg($imagenRedimensionada, $rutaDestino, $calidad);
    } elseif ($tipoImagen == IMAGETYPE_PNG) {
        $calidadPng = ($calidad / 10);
        imagepng($imagenRedimensionada, $rutaDestino, $calidadPng);
    }

    imagedestroy($imagenOriginal);
    imagedestroy($imagenRedimensionada);

    return true;
}
?>
