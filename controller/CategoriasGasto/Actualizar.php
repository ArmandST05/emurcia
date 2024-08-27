<?php
include('../../model/ModelCategoriaGasto.php');
$modelCategoriaGasto = new ModelCategoriaGasto();

$id = $_POST["id"];
$nombre = strtoupper($_POST["nombre"]);
$tipoGasto = $_POST["tipoGasto"];

$categoria = $modelCategoriaGasto->actualizar($id, $nombre);
if ($categoria) {
	echo "<script>
		alert('Categoría actualizada');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
} else {
	echo "<script>
		alert('Ha ocurrido un error al actualizar la categoría');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
