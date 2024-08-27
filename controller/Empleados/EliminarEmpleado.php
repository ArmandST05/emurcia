<?php
include('../../model/ModelEmpleado.php'); // Asegúrate de que el modelo de empleados esté incluido adecuadamente.

/* Variable para llamar al método del modelo */
$modelEmpleado = new ModelEmpleado();

/* Obtén el ID del empleado a eliminar. */
if (isset($_POST["id"])) {
$id_empleado =$_POST["id"];
}


// Verificamos si el empleado existe por ID.
$empleado = $modelEmpleado->obtenerEmpleadoPorId($id_empleado);

if (empty($empleado)) {
    echo "<script>
        alert('El empleado no existe');
        window.location.href = '../../view/index.php?action=empleados/index.php';
      </script>";
} else {
    
    $modelEmpleado->eliminarEmpleado($id_empleado);
    echo "<script>
        alert('Empleado marcado como inactivo');
        window.location.href = '../../view/index.php?action=empleados/index.php';
      </script>";
}
?>
