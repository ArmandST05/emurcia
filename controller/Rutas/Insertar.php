<?php
/* Variable para llamar método de Modelo*/
include('../../model/ModelRuta.php');
$modelRuta = new ModelRuta();
include('../../model/ModelPermiso.php');
$modelPermiso = new ModelPermiso();

/* Obtenemos los datos*/
$ruta = $_POST["ruta"];
$zonaId = $_POST["zona"];
$txtciudad = $_POST["txtciudad"];
$txtcolonia = $_POST["txtcolonia"];
$tipoRuta = $_POST["tipo_ruta"];
$tipoGananciaRutaId = $_POST["tipo_ganancia_ruta_id"];
$capacidad = isset($_POST["capacidad"]) ?  $_POST["capacidad"]:null;
$inventarioMinimo = $_POST["inventarioMinimo"];
$telefono = $_POST["telefono"];
$permisos = isset($_POST["permisos"]) ?  $_POST["permisos"]:null;
$vendedor1 = $_POST["vendedorSelect"] ? $_POST["vendedorSelect"] : null;
$vendedor2 = $_POST["ayudanteSelect"]? $_POST["ayudanteSelect"] : null;

if (empty($telefono)) $telefono = null;

if ($capacidad == "No Aplica" || $capacidad == "") {
	$capacidad = 0;
}

$validarRuta = $modelRuta->verificarRuta($ruta);

if (!empty($validarRuta)) {
	echo "<script> 
		alert('El nombre de la ruta ya existe en la base de datos');
		window.location.href = '../../view/index.php?action=rutas/index.php';
		</script>";
} elseif (!empty($vendedor1) && !empty($vendedor2) && $vendedor1 == $vendedor2) {
	echo "<script> 
	alert('Ambos vendedores no pueden ser el mismo empleado');
	window.location.href = '../../view/index.php?action=rutas/index.php';
	</script>";
} else { //Todo esta bien, agregamos a la BD
	$rutaId = $nuevaRuta = $modelRuta->insertar($ruta, $vendedor1, $vendedor2, $zonaId, $txtciudad, $txtcolonia, $tipoRuta,$tipoGananciaRutaId, $capacidad, $inventarioMinimo, $telefono);

	//Insertar los permisos
	if ($rutaId && !empty($permisos)) {
		foreach ($permisos as $key => $permiso) {
			$insertarPermiso = $modelPermiso->insertarPermisoRuta(
				$key, //PermisoId
				$rutaId //Ruta Insertada
			);
		}
	}
	$_SESSION['alerta-tipo'] = 'success';
	$_SESSION['alerta-mensaje'] = 'Ruta agregada exitosamente';

	if ($rutaId) $mensaje = "Ruta agregada existosamente";
	else $mensaje = "Ha ocurrido un error al agregar la ruta";

	echo "<script>
			alert('$mensaje');
			window.location.href = '../../view/index.php?action=rutas/index.php';
		</script>";
}
