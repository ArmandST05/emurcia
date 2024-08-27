<?php
include('../../model/ModelMeta.php');
/* Variable para llamar al método del modelo */
$modelMeta = new ModelMeta();

/* Obtenemos los datos del formulario */
$metaId = $_POST["id"];
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$tipoEmpleado = isset($_POST["tipoEmpleado"]) ? $_POST["tipoEmpleado"]:null;
$tipoGananciaRuta = isset($_POST["tipoGananciaRuta"]) ? $_POST["tipoGananciaRuta"]:1;//Normal o mejores rutas
$ruta = isset($_POST["ruta"]) ? $_POST["ruta"]:null;

$meta1 = $_POST["meta1"];
$meta2 = $_POST["meta2"];
$meta3 = $_POST["meta3"];
$meta4 = $_POST["meta4"];
$meta5 = $_POST["meta5"];
$comision1 = $_POST["comision1"];
$comision2 = $_POST["comision2"];
$comision3 = $_POST["comision3"];
$comision4 = $_POST["comision4"];
$comision5 = $_POST["comision5"];
if (isset($_POST["descuento"])) {
    $descuento = $_POST["descuento"] ? $_POST["descuento"] : 0;
} else {
    $descuento = 0;
}
$gruposVenta = array();
if (isset($_POST["total_pipas"]) && $_POST["total_pipas"] !== '') {
    $gruposVenta[] = $_POST["total_pipas"];
}
if (isset($_POST["pipas_descuento"]) && $_POST["pipas_descuento"] !== '') {
    $gruposVenta[] = $_POST["pipas_descuento"];
}
if (isset($_POST["total_estaciones"]) && $_POST["total_estaciones"] !== '') {
    $gruposVenta[] = $_POST["total_estaciones"];
}
if (isset($_POST["total_cilindros"]) && $_POST["total_cilindros"] !== '') {
    $gruposVenta[] = $_POST["total_cilindros"];
}

if (empty($nombre) || empty($tipoEmpleado)) {
    // Verificamos que los datos no sean nulos o vacíos.
    echo "<script>
        alert('Completa los datos obligatorios');
        window.location.href = '../../view/index.php?action=metas/index.php';
      </script>";
}
// Obtener el empleado existente
$metaRow = $modelMeta->obtenerMetaPorId($metaId);

if (empty($metaRow)) {
    // Si la meta no existe, mostrar un mensaje de error y redirigir
    mostrarMensajeYRedirigir("El la meta no existe", '../../view/index.php?action=metas/index.php');
} elseif (empty($nombre)) {
    // Si faltan campos obligatorios, mostrar un mensaje de error y redirigir
    mostrarMensajeYRedirigir("Faltan campos obligatorios", '../../view/index.php?action=metas/editar.php&id=' . $metaId);
} else {
    // Actualizar la información del empleado
    $modelMeta->actualizarMeta($metaId, $nombre, $descripcion, $tipoEmpleado, $meta1, $meta2, $meta3, $meta4, $meta5, $comision1, $comision2, $comision3, $comision4, $comision5, $ruta,$tipoGananciaRuta, $descuento);
    
    if ($tipoEmpleado == 2) {
        foreach ($gruposVenta as $grupo) {
            $modelMeta->insertarDetalleGerente($metaRow['zona_id'], $metaId, $grupo);
        }
    }
    mostrarMensajeYRedirigir("Meta actualizada exitosamente", '../../view/index.php?action=metas/index.php');
}

function mostrarMensajeYRedirigir($mensaje, $url)
{
    echo "<script>
            alert('$mensaje');
            window.location.href = '$url';
          </script>";
    exit(); // Salir del script para evitar ejecución adicional
}
