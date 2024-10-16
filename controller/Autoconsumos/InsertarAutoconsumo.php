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
            $directorioDestino = '../../view/autoconsumos/comprobantes';
    
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
?>
