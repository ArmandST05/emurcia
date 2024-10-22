<?php
include('../../model/ModelAutoconsumo.php');    
/*variable para llamar metodo de Modelo*/
$modelAutoconsumo = new ModelAutoconsumo();
date_default_timezone_set('America/Mexico_City');

/*Obtenemos los datos*/
$diaIni =  ($_POST["diaIni"]) ? $_POST["diaIni"]: date("d");
$mesIni =  ($_POST["mesIni"]) ? $_POST["mesIni"]: date("m");
$anioIni =  ($_POST["anioIni"]) ? $_POST["anioIni"]: date("Y");

$diaFin =  ($_POST["diaFin"]) ? $_POST["diaFin"]: date("d");
$mesFin =  ($_POST["mesFin"]) ? $_POST["mesFin"]: date("m");
$anioFin =  ($_POST["anioFin"]) ? $_POST["anioFin"]: date("Y");

$fechaInicio = $anioIni."-".$mesIni."-".$diaIni; 
$fechaFin = $anioFin."-".$mesFin."-".$diaFin; 

$rutaId = $_POST["ruta"];
$combustible = $_POST["combustible"];
$litros = $_POST["litros"];
$costo_litro = $_POST["costo_litro"];
$kmi = $_POST["kmi"];
$kmf = $_POST["kmf"];

// Validar no nulos
if(strlen($rutaId) < 1 || strlen($combustible) < 1 || strlen($litros) < 1 || strlen($costo_litro) < 1 || 
    strlen($kmi) < 1 || strlen($kmf) < 1) {
    echo "<script> 
            alert('Ingresa todos los datos por favor');
            window.location.href = '../../view/index.php?action=autoconsumos/nuevo.php';
          </script>";
}
else {
    if (isset($_FILES['comprobante'])) {
        if ($_FILES['comprobante']['error'] === 0) {
            $nombreArchivo = $_FILES['comprobante']['name'];
            $rutaTemporal = $_FILES['comprobante']['tmp_name'];
            $directorioDestino = '../../view/autoconsumos/comprobantes/';
            $maxSize = 1 * 1024 * 1024; // 5 MB en bytes
            $nuevoAncho = 800; // Redimensionar ancho máximo
            $nuevoAlto = 600;  // Redimensionar alto máximo

            // Verificar tamaño del archivo
            if ($_FILES['comprobante']['size'] > $maxSize) {
                // Redimensionar y optimizar imagen si es mayor de 5 MB
                redimensionarYOptimizarImagen($rutaTemporal, $rutaTemporal, $nuevoAncho, $nuevoAlto, 70); // Reducimos calidad al 70%

                // Verificar de nuevo si después de la optimización el archivo sigue siendo mayor de 5 MB
                if (filesize($rutaTemporal) > $maxSize) {
                    echo "<script> 
                    alert('El archivo sigue siendo mayor a 5 MB incluso después de optimizar.'); 
                    window.location.href = '../../view/index.php?action=autoconsumos/nuevo.php'; 
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
                $comprobante = $rutaFinal;
            } else {
                echo "<script> 
                alert('Error al mover el archivo.'); 
                window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
                </script>";
            }
        } else {
            // Ver error de la subida
            $error = $_FILES['comprobante']['error'];
            echo "<script> 
            alert('Error en la subida de archivo: Código de error $error'); 
            window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
            </script>";
        }
    } else {
        echo "<script> 
        alert('No se envió ningún archivo.'); 
        window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
        </script>";
    }
    
    // Llamar al método para insertar en la base de datos
    $autoconsumo = $modelAutoconsumo->insertar($rutaId, $combustible, $litros, $costo_litro, $kmi, $kmf, $fechaInicio, $fechaFin, $comprobante);

    if($autoconsumo) {
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
}

// Función para redimensionar y optimizar imagenes solo si es mayor de 5 MB
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
