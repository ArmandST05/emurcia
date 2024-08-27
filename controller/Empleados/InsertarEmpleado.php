<?php
include('../../model/ModelEmpleado.php'); 
/* Variable para llamar al método del modelo */
$modelEmpleado = new ModelEmpleado();
/* Obtenemos los datos del formulario */
$nombre = strtoupper($_POST["nombre"]);
$tipo_empleado_id = $_POST["puesto"];
$zona_id=$_POST["zona_id"];
$sueldo_base=$_POST["sueldo_base"];
$infonavit=$_POST["infonavit"];

// $ruta = isset($_POST["rutaselect"]) && !$_POST["rutaselect"]=="" ? $_POST["rutaselect"] : null; // Verifica si $ruta está definido, si no, asigna null.
$estatus = 1; // Establecemos el estado como "activo" por defecto al agregar un nuevo empleado.
// Verificamos si el empleado ya existe por nombre o cualquier otro criterio que desees utilizar.

$validarEmpleado = $modelEmpleado->verificarNombre($nombre, $zona_id);
if (!empty($validarEmpleado)) {
    echo "<script>
        alert('El empleado ya existe');
        window.location.href = '../../view/index.php?action=empleados/nuevo_empleado.php';
      </script>";
} else if (empty($nombre) || empty($tipo_empleado_id)) {
    // Verificamos que los datos no sean nulos o vacíos.
    echo "<script>
        alert('Completa los datos obligatorios por favor');
        window.location.href = '../../view/index.php?action=empleados/index.php';
      </script>";
} else {
    // Todo está bien, agregamos al empleado a la BD.
    $nuevoEmpleado = $modelEmpleado->agregarEmpleado($nombre, $tipo_empleado_id, $sueldo_base,$infonavit , $zona_id);
    echo "<script>
        alert('Empleado agregado exitosamente');
        window.location.href = '../../view/index.php?action=empleados/index.php';
      </script>";
}
?>