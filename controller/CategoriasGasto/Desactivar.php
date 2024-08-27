<?php
  include('../../model/ModelCategoriaGasto.php'); 
  $modelCategoriaGasto = new ModelCategoriaGasto();
  include('../../model/ModelConceptoGasto.php'); 
  $modelConceptoGasto = new ModelConceptoGasto();

  $id = $_POST["id"];
  $desactivar = $modelCategoriaGasto->desactivar($id);    
  $desactivarConceptos = $modelConceptoGasto->desactivarPorCategoria($id);      
  if($desactivar) echo $id;
?>
