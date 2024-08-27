<?php
include('../../model/ModelCategoriaGasto.php');
$modelCategoriaGasto = new ModelCategoriaGasto();

$tipoGasto = $_POST["tipoGasto"];
$nombre = strtoupper($_POST["nombre"]);
$nuevaCategoria = $modelCategoriaGasto->insertar($tipoGasto,$nombre);

if($nuevaCategoria){
	echo "<script>
		alert('Categoría agregada');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
else{
	echo "<script>
		alert('Ha ocurrido un error al agregar la categoría');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
?>