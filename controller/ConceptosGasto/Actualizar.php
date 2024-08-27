<?php
include('../../model/ModelConceptoGasto.php');
$modelConceptoGasto = new ModelConceptoGasto();

$id = $_POST["id"];
$categoriaId = $_POST["categoria"];
$nombre = strtoupper($_POST["nombre"]);
$tipoGasto = $_POST["tipoGasto"];

$concepto = $modelConceptoGasto->actualizar($id, $categoriaId, $nombre);
if ($concepto) {
	echo "<script>
		alert('Concepto actualizado');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
} else {
	echo "<script>
		alert('Ha ocurrido un error al actualizar el concepto');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
