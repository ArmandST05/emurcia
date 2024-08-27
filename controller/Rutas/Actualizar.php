<?php
/*Variable para llamar metodo de Modelo*/
include('../../model/ModelRuta.php');
$modelRuta = new ModelRuta();
include('../../model/ModelZona.php');
$modelZona = new ModelZona();
include('../../model/ModelPermiso.php');
$modelPermiso = new ModelPermiso();

/*Obtenemos los datos*/
$id = $_POST["rutaId"];
$rutaNombre = $_POST["ruta"];
$vendedor1 = isset($_POST["vendedorSelect"]) ? $_POST["vendedorSelect"] : null;
$vendedor2 = isset($_POST["ayudanteSelect"]) ? $_POST["ayudanteSelect"] : null;
$zona = $_POST["zona"];
$ciudad = isset($_POST["ciudad"]) ? $_POST["ciudad"]:null;
$listaCiudades = $_POST["listaCiudades"];
$listaColonias = $_POST["listaColonias"];
$tipoRuta = $_POST["tipo_ruta"];
$tipoGananciaRutaId = $_POST["tipo_ganancia_ruta_id"];
$capacidad = isset($_POST["capacidad"]) ?  $_POST["capacidad"]:null;
$inventarioMinimo = $_POST["inventarioMinimo"];
$telefono = $_POST["telefono"];
if (empty($telefono)) $telefono = null;
$permisos = isset($_POST["permisos"]) ?  $_POST["permisos"]:null;


if ($vendedor1 == $vendedor2 && $vendedor1) {
	echo "<script> 
		alert('Ambos vendedores no pueden ser el mismo empleado');
		window.location.href = '../../view/index.php?action=rutas/index.php';
		</script>";
} else {
	if ($capacidad == "No Aplica") {
		$capacidad = 0;
	}

	$modelRuta->actualizar($id, $rutaNombre, $vendedor1, $vendedor2,$zona, $listaCiudades, $listaColonias, $tipoRuta,$tipoGananciaRutaId, $capacidad, $inventarioMinimo, $telefono);

	//Se actualizan los permisos eliminándolos y volviéndolos a insertar
	$eliminarPermisos = $modelPermiso->eliminarPorRuta($id);

	if (!empty($permisos)) {
		foreach ($permisos as $key => $permiso) {
			$insertarPermiso = $modelPermiso->insertarPermisoRuta(
				$key, //PermisoId
				$id //Ruta
			);
		}
	}

	echo "<script>
		alert('Ruta actualizada correctamente');
		window.location.href = '../../view/index.php?action=rutas/index.php'; 
		</script>";
}
