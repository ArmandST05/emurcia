<?php
include('../../model/ModelCredito.php');
$mode_credit = new ModelCredito();

// Obtenemos los datos
$fecha = $_POST["formfecha"];
$idcliente = $_POST["idcliente"];
$nombre = $_POST["name"];
$domicilio = $_POST["dom"];
$colonia = $_POST["col"];
$notafac = $_POST["nota"];
$foliofisc = $_POST["folfis"];
$precio = $_POST["pre"];
$litros = $_POST["lit"];
$importe = $_POST["imp"];
$descuento = $_POST["desc"];
$vencimiento = $_POST["formatofecha"];
$disponible = $_POST["disp"];
$usado = $_POST["used"];
$vendedor = $_POST["salesman"];
$zona = $_POST["zone"];
$dia = $_POST["day"];
$mes = $_POST["month"];
$anio = $_POST["year"];
$pre_des = $_POST["pre_des"];

// Calcular precio ajustado
if ($pre_des == 0) {
    $precio_chi = $precio;
} else {
    $precio_chi = ($precio) - ($precio - $pre_des);
}

// Calculo de nuevos créditos
$nuevo_credito = $disponible - $importe;
$new_used = $usado + $importe;

// Verificamos que la nota no exista
$validarnota = $mode_credit->verificarnota($notafac, $zona, $foliofisc);

if (!empty($validarnota)) {
    echo "<script> 
            alert('La nota o factura ya existe');
            window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
          </script>";
} else {
    if ($nuevo_credito < 0) {
        echo "<script> 
                alert('No se pudo otorgar el crédito ya que el cliente no cuenta con ese importe');
                window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
              </script>";
    } else {
        // Validamos los no nulos
        if (strlen($fecha) < 1) {
            echo "<script> 
                    alert('Falta la fecha del cliente');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($nombre) < 1) {
            echo "<script> 
                    alert('Falta el nombre del cliente');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($domicilio) < 1) {
            echo "<script> 
                    alert('Falta el domicilio');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($colonia) < 1) {
            echo "<script> 
                    alert('Falta la colonia');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($notafac) < 1) {
            echo "<script> 
                    alert('Falta el número de nota o factura');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($precio) < 1) {
            echo "<script> 
                    alert('Falta el precio');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($litros) < 1) {
            echo "<script> 
                    alert('Faltan los litros');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($importe) < 1) {
            echo "<script> 
                    alert('Falta el importe');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($vencimiento) < 1) {
            echo "<script> 
                    alert('Falta la fecha de vencimiento');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else if (strlen($foliofisc) < 1) {
            echo "<script> 
                    alert('Falta el folio fiscal');
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                  </script>";
        } else {
            // Procesar el archivo del comprobante
            if (isset($_FILES['comprobante'])) {
                if ($_FILES['comprobante']['error'] === 0) {
                    $nombreArchivo = $_FILES['comprobante']['name'];
                    $rutaTemporal = $_FILES['comprobante']['tmp_name'];
                    $directorioDestino = '../../view/creditos/comprobantes/';
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
                            window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
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
                        window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                        </script>";
                        exit;
                    }
                } else {
                    // Ver error de la subida
                    $error = $_FILES['comprobante']['error'];
                    echo "<script> 
                    alert('Error en la subida de archivo: Código de error $error'); 
                    window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                    </script>";
                    exit;
                }
            } else {
                echo "<script> 
                alert('No se envió ningún archivo.'); 
                window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=" . $idcliente . "'; 
                </script>";
                exit;
            }

            $mode_credit->addcredit($idcliente, $fecha, $nombre, $domicilio, $colonia, $notafac, $foliofisc, $precio_chi, $litros, $importe, $vencimiento, $vendedor, $zona, $dia, $mes, $anio, $descuento, $comprobanteCreditoGas);
            $mode_credit->updatecredit($nuevo_credito, $idcliente, $new_used);
            echo "<script> 
                    alert('Crédito registrado');
                    window.location.href = '../../view/index.php?action=creditos/index.php';    
                  </script>";
        }
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