<<<<<<< HEAD
<?php
include('../../model/ModelGasto.php');
$modelGasto = new ModelGasto();

$mes = $_POST["mes"];
$anio = $_POST["anio"];
$rutaId = $_POST["rutaId"];
$concepto = $_POST["concepto"];
$cantidad = $_POST["cantidad"];
$observaciones = $_POST["observaciones"];
$zona = $_POST["zona"];
$zonaId = $_POST["zonaId"];
$comprobante = null; // Inicializamos la variable del comprobante

// Validar no nulos
if (strlen($mes) < 1 || strlen($anio) < 1 || strlen($rutaId) < 1 || strlen($concepto) < 1 || 
    strlen($cantidad) < 1 || strlen($zonaId) < 1) {
    echo "<script> 
            alert('Ingresa todos los datos por favor');
            window.location.href = '../../view/index.php?action=gastosruta/nuevo.php';
          </script>";
    exit;
}

// Manejo del comprobante
if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === 0) {
    $nombreArchivo = $_FILES['comprobante']['name'];
    $rutaTemporal = $_FILES['comprobante']['tmp_name'];
    $directorioDestino = '../../view/gastosruta/comprobantes/';
    $maxSize = 5 * 1024 * 1024; // 5 MB en bytes

    // Verificar tamaño del archivo
    if ($_FILES['comprobante']['size'] > $maxSize) {
        echo "<script> 
                alert('El archivo es mayor a 5 MB. Por favor, sube un archivo más pequeño.'); 
                window.location.href = '../../view/index.php?action=gastosruta/nuevo.php'; 
              </script>";
        exit;
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
        echo "<script> 
                alert('Error al mover el archivo.'); 
                window.location.href = '../../view/index.php?action=gastosruta/nuevo.php'; 
              </script>";
        exit;
    }
}

// Insertar el gasto con el comprobante (si se subió)
$modelGasto->agregarGastoRuta($mes, $anio, $rutaId, $concepto, $cantidad, $observaciones, $zona, $zonaId, $comprobante);

echo "<script> 
        alert('Gasto registrado correctamente'); 
        window.location.href = '../../view/index.php?action=gastosruta/index.php'; 
      </script>";
?>
=======
<?php
include('../../model/ModelGasto.php');    
$modelGasto = new ModelGasto();
date_default_timezone_set('America/Mexico_City');

// Obtener datos del formulario
$mes = $_POST["mes"];
$anio = $_POST["anio"];
$rutaId = $_POST["rutaId"];
$concepto = $_POST["concepto"];
$cantidad = $_POST["cantidad"];
$observaciones = $_POST["observaciones"];
$zona = $_POST["zona"];
$zonaId = $_POST["zonaId"];
$comprobante = null; // 🔴 Inicializar la variable para evitar que tome valores incorrectos

// Validar datos no nulos
if (strlen($mes) < 1 || strlen($anio) < 1 || strlen($rutaId) < 1 || strlen($concepto) < 1 || 
    strlen($cantidad) < 1 || strlen($zonaId) < 1) {
    echo "<script> 
            alert('Ingresa todos los datos por favor');
            window.location.href = '../../view/index.php?action=gastosruta/nuevo.php';
          </script>";
    exit;
}

// Procesar la subida del archivo si se adjunta un comprobante
if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === 0) {
    $nombreArchivo = time() . "_" . basename($_FILES['comprobante']['name']); // Evitar nombres duplicados
    $rutaTemporal = $_FILES['comprobante']['tmp_name'];
    $directorioDestino = '../../view/gastosruta/comprobantes/';
    $maxSize = 5 * 1024 * 1024; // 5 MB
    $nuevoAncho = 800;
    $nuevoAlto = 600;

    // Verificar tamaño del archivo
    if ($_FILES['comprobante']['size'] > $maxSize) {
        // Redimensionar y optimizar imagen
        redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70);

        // Verificar de nuevo si sigue siendo mayor a 5MB
        if (filesize($rutaTemporal) > $maxSize) {
            echo "<script> 
                    alert('El archivo sigue siendo mayor a 5 MB incluso después de optimizar.'); 
                    window.location.href = '../../view/index.php?action=gastosruta/nuevo.php'; 
                  </script>";
            exit;
        }
    }

    // Crear directorio si no existe
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true);
    }

    // Ruta final del archivo
    $rutaFinal = $directorioDestino . $nombreArchivo;
    if (move_uploaded_file($rutaTemporal, $rutaFinal)) {
        $comprobante = $rutaFinal;
    } else {
        echo "<script> 
                alert('Error al mover el archivo.'); 
                window.location.href = '../../view/index.php?action=gastosruta/index.php'; 
              </script>";
        exit;
    }
}

$gastoInsertado = $modelGasto->agregarGastoRuta($mes, $anio, $rutaId, $concepto, $cantidad, $observaciones, $zonaId, $comprobante);

if ($gastoInsertado) {
    echo "<script> 
            alert('Gasto registrado correctamente'); 
            window.location.href = '../../view/index.php?action=gastosruta/index.php'; 
          </script>";
} else {
    echo "<script> 
            alert('Ha ocurrido un error al registrar el gasto'); 
            window.location.href = '../../view/index.php?action=gastosruta/index.php'; 
          </script>";
}

// Función para redimensionar y optimizar imágenes
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

    // Crear imagen desde el archivo original
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

    // Crear imagen vacía con las nuevas dimensiones
    $imagenRedimensionada = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

    // Mantener transparencia en PNG
    if ($tipoImagen == IMAGETYPE_PNG) {
        imagealphablending($imagenRedimensionada, false);
        imagesavealpha($imagenRedimensionada, true);
    }

    // Redimensionar imagen
    imagecopyresampled($imagenRedimensionada, $imagenOriginal, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

    // Guardar imagen optimizada
    if ($tipoImagen == IMAGETYPE_JPEG) {
        imagejpeg($imagenRedimensionada, $rutaDestino, $calidad);
    } elseif ($tipoImagen == IMAGETYPE_PNG) {
        $calidadPng = ($calidad / 10);
        imagepng($imagenRedimensionada, $rutaDestino, $calidadPng);
    }

    // Liberar memoria
    imagedestroy($imagenOriginal);
    imagedestroy($imagenRedimensionada);

    return true;
}
?>
>>>>>>> 545fb7b87c3c1357fd81bd614d4e9552b29f5d45
