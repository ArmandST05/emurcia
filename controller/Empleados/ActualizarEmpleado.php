<?php
include('../../model/ModelEmpleado.php');

$modelEmpleado = new ModelEmpleado();

// Obtener los datos del formulario
$id=$_POST["id"];
$nombre = strtoupper($_POST["nombre"]);
$tipo_empleado_id = $_POST["puesto"];
$sueldo_base=$_POST["sueldo_base"];
$infonavit=$_POST["infonavit"];
$estatus= $_POST["estatus"];
$zona_id = $_POST["zona_id"];

// Obtener el empleado existente
$empleado = $modelEmpleado->obtenerEmpleadoPorId($id);

if (empty($empleado)) {
    // Si el empleado no existe, mostrar un mensaje de error y redirigir
    mostrarMensajeYRedirigir("El empleado no existe", '../../view/index.php?action=empleados/index.php');
} elseif (empty($nombre) || empty($tipo_empleado_id)) {
    // Si faltan campos obligatorios, mostrar un mensaje de error y redirigir
    mostrarMensajeYRedirigir("Faltan campos obligatorios", '../../view/index.php?action=empleados/editar_empleado.php?id=' . $id);
} else {
    // Actualizar la información del empleado
    $modelEmpleado->actualizarEmpleado($id, $nombre, $tipo_empleado_id, $sueldo_base ,$infonavit, $estatus,$zona_id);

    // Mostrar un mensaje de éxito y redirigir
    mostrarMensajeYRedirigir("Empleado actualizado exitosamente", '../../view/index.php?action=empleados/index.php');
}

function mostrarMensajeYRedirigir($mensaje, $url) {
    echo "<script>
            alert('$mensaje');
            window.location.href = '$url';
          </script>";
    exit(); // Salir del script para evitar ejecución adicional
}
?>
