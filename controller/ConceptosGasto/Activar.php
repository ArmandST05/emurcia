<?php
  include('../../model/ModelConceptoGasto.php'); 
  $modelConceptoGasto = new ModelConceptoGasto();

  $id = $_POST["id"];
  $modelConceptoGasto->activar($id);          
  echo $id;
?>