<?php
  include('../../model/ModelCategoriaGasto.php'); 
  $modelCategoriaGasto = new ModelCategoriaGasto();
  include('../../model/ModelConceptoGasto.php'); 
  $modelConceptoGasto = new ModelConceptoGasto();

  $id = $_POST["id"];
  $activar = $modelCategoriaGasto->activar($id);  
  $activarConceptos = $modelConceptoGasto->activarPorCategoria($id);     
  if($activar) echo $id;
?>
