<?php
include('../../model/ModelConceptoGasto.php');
$modelConceptoGasto = new ModelConceptoGasto();

$categoriaId = $_POST["categoria"];
$nombre = strtoupper($_POST["nombre"]);
$concepto = $modelConceptoGasto->insertar($categoriaId,$nombre);
$tipoGasto = $_POST["tipoGasto"];

if($concepto){
	echo "<script>
		alert('Concepto agregado');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
else{
	echo "<script>
		alert('Ha ocurrido un error al agregar el concepto');
		window.location.href = '../../view/index.php?action=gastosconfiguracion/categorias_conceptos.php&tipoGasto=".$tipoGasto."';
	</script>";
}
