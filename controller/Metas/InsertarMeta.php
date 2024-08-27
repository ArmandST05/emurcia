<?php
include('../../model/ModelMeta.php');
/* Variable para llamar al método del modelo */
$modelMeta = new ModelMeta();

/* Obtenemos los datos del formulario */
$nombre = $_POST["nombre"];
$descripcion = $_POST["descripcion"];
$zona = $_POST["zona"];
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

$descuento = (isset($_POST["descuento"])) ? $_POST["descuento"] : 0;

$grupoVenta = array();
if (isset($_POST["total_pipas"]) && $_POST["total_pipas"] !== '') {
  $grupoVenta[] = $_POST["total_pipas"];
}
if (isset($_POST["pipas_descuento"]) && $_POST["pipas_descuento"] !== '') {
  $grupoVenta[] = $_POST["pipas_descuento"];
}
if (isset($_POST["total_estaciones"]) && $_POST["total_estaciones"] !== '') {
  $grupoVenta[] = $_POST["total_estaciones"];
}
if (isset($_POST["total_cilindros"]) && $_POST["total_cilindros"] !== '') {
  $grupoVenta[] = $_POST["total_cilindros"];
}

if (empty($nombre)  ||  empty($zona) || empty($tipoEmpleado)) {
  // Verificamos que los datos no sean nulos o vacíos.
  echo "<script>
          alert('Completa los datos obligatorios');
          window.location.href = '../../view/index.php?action=metas/index.php';
        </script>";
} else {
  // todo está bien, agregamos la meta a la BD.
  try {
    if ($tipoEmpleado == 2) { //Gerentes
      $nuevaMeta = $modelMeta->insertarMeta($nombre, $descripcion, $tipoEmpleado, $zona, $ruta,$tipoGananciaRuta, $meta1, $meta2, $meta3, $meta4, $meta5, $comision1, $comision2, $comision3, $comision4, $comision5, $descuento);

      foreach ($grupoVenta as $grupo) {
        $modelMeta->insertarDetalleGerente($zona, $nuevaMeta, $grupo);
      }
      echo "<script>
          alert('Meta agregada exitosamente');
          window.location.href = '../../view/index.php?action=metas/index.php';
        </script>";
    } else {
      $existeMeta = $modelMeta->validarExisteMeta($tipoEmpleado, $descuento, $zona, $ruta,$tipoGananciaRuta);

      if ($existeMeta->total == 0) {
        $nuevaMeta = $modelMeta->insertarMeta($nombre, $descripcion, $tipoEmpleado, $zona, $ruta,$tipoGananciaRuta, $meta1, $meta2, $meta3, $meta4, $meta5, $comision1, $comision2, $comision3, $comision4, $comision5, $descuento);
        echo "<script>
            alert('Meta agregada exitosamente');
            window.location.href = '../../view/index.php?action=metas/index.php';
            </script>";
      } else {
        echo "<script>
                alert('Ya existe una meta asociada para la misma zona y tipo de empleado con las mismas características');
                window.location.href = '../../view/index.php?action=metas/nuevo.php';
              </script>";
      }
    }
  } catch (Exception $error) {
    echo "<script>
        alert('Error al insertar la nueva meta');
        window.location.href = '../../view/index.php?action=metas/index.php';
      </script>";
  }
}
