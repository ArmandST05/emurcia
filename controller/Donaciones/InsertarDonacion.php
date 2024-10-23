<?php
include('../../view/session1.php');
include('../../model/ModelDonacion.php');	

/* Variable para llamar método de Modelo */
$modelDonacion = new ModelDonacion();
date_default_timezone_set('America/Mexico_City');

/* Obtenemos los datos del formulario */
$fecha = $_POST["fecha"];
$cantidad = $_POST["cantidad"];
$zonaId = $_SESSION["zonaId"];
$comentario = $_POST["comentario"];

// Validar que los campos no sean nulos
if (strlen($fecha) < 1 || strlen($cantidad) < 1 || strlen($comentario) < 1) {
    echo "<script> 
            alert('Ingresa todos los datos por favor');
            window.location.href = '../../view/index.php?action=donaciones/nuevo.php';
          </script>";
    exit;
} else {
    // Validar el archivo de comprobante
    if (isset($_FILES['comprobante'])) {
        if ($_FILES['comprobante']['error'] === 0) {
            $nombreArchivo = $_FILES['comprobante']['name'];
            $rutaTemporal = $_FILES['comprobante']['tmp_name'];
            $directorioDestino = '../../view/donaciones/comprobantes/';
            $maxSize = 256 * 1024; // 1 MB en bytes
            $nuevoAncho = 400; // Redimensionar ancho máximo
            $nuevoAlto = 200;  // Redimensionar alto máximo

            // Verificar tamaño del archivo
            if ($_FILES['comprobante']['size'] > $maxSize) {
                // Redimensionar y optimizar imagen si es mayor de 1 MB
                if (!redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70)) {
                    echo "<script> 
                            alert('Error al procesar la imagen.'); 
                            window.location.href = '../../view/index.php?action=donaciones/nuevo.php'; 
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
                $comprobanteDonacion = $rutaFinal; // Guardar la ruta final del comprobante
            } else {
                echo "<script> 
                        alert('Error al mover el archivo.'); 
                        window.location.href = '../../view/index.php?action=donaciones/index.php'; 
                      </script>";
                exit;
            }
        } else {
            // Ver error de la subida
            $error = $_FILES['comprobante']['error'];
            echo "<script> 
                    alert('Error en la subida de archivo: Código de error $error'); 
                    window.location.href = '../../view/index.php?action=donaciones/index.php'; 
                  </script>";
            exit;
        }
    } else {
        echo "<script> 
                alert('No se envió ningún archivo.'); 
                window.location.href = '../../view/index.php?action=donaciones/index.php'; 
              </script>";
        exit;
    }

    // Llamar al método para insertar en la base de datos
    $donacion = $modelDonacion->insertar($fecha, $cantidad, $zonaId, $comentario, $comprobanteDonacion);

    if ($donacion) {
        echo "<script> 
                alert('Donación agregada correctamente'); 
                window.location.href = '../../view/index.php?action=donaciones/index.php'; 
              </script>";
    } else {
        echo "<script> 
                alert('Ha ocurrido un error al agregar la donación'); 
                window.location.href = '../../view/index.php?action=donaciones/index.php'; 
              </script>";
    }
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
