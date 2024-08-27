<?php
include('../../model/ModelOrigenGasto.php');
$modelOrigenGasto = new ModelOrigenGasto();

$id = $_POST["id"];
$nombre = strtoupper($_POST["nombre"]);

$origen = $modelOrigenGasto->actualizar($id, $nombre);
if ($origen) {
	echo "<script>
		alert('Origen actualizado');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/origenes.php';
	</script>";
} else {
	echo "<script>
		alert('Ha ocurrido un error al actualizar el origen');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/origenes.php';
	</script>";
}
