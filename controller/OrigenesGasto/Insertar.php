<?php
include('../../model/ModelOrigenGasto.php');
$modelOrigenGasto = new ModelOrigenGasto();

$nombre = strtoupper($_POST["nombre"]);
$origen = $modelOrigenGasto->insertar($nombre);

if ($origen) {
	echo "<script>
		alert('Origen agregado');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/origenes.php';
	</script>";
} else {
	echo "<script>
		alert('Ha ocurrido un error al agregar el origen');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/origenes.php';
	</script>";
}
