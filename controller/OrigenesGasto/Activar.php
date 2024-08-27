<?php
  include('../../model/ModelOrigenGasto.php'); 
  $modelOrigenGasto = new ModelOrigenGasto();

  $id = $_POST["id"];
  $modelOrigenGasto->activar($id);          
  echo $id;
?>