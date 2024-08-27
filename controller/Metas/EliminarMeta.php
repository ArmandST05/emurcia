<?php
include('../../model/ModelMeta.php'); // Asegúrate de que el modelo de empleados esté incluido adecuadamente.

/* Variable para llamar al método del modelo */
$modelMeta = new ModelMeta();

/* Obtén el ID del empleado a eliminar. */
if (isset($_POST["id"])) {
$id_meta =$_POST["id"];
}



$meta = $modelMeta->obtenerMetaPorId($id_meta);

if ($meta === false || empty($meta)) {
    echo "<script>
        alert('La meta no existe');
        setTimeout(function() {
            window.location.href = '../../view/index.php?action=metas/index.php';
        }, 1000); // Retraso de 1 segundo (1000 ms) antes de redirigir
      </script>";
} else {
    $modelMeta->EliminarMeta($id_meta);
    echo "<script>
    alert('Meta eliminada exitosamente');
    window.location.href = '../../view/index.php?action=metas/index.php';
  </script>";
}
?>