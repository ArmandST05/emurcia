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
