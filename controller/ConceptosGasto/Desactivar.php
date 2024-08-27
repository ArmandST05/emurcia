<?php
  include('../../model/ModelConceptoGasto.php'); 
  $modelConceptoGasto = new ModelConceptoGasto();

  $id = $_POST["id"];
  $modelConceptoGasto->desactivar($id);     
  echo $id;
