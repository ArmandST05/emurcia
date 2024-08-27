<?php
  include('../../model/ModelRuta.php'); 
  $modelRuta = new ModelRuta();

  $id = $_POST["id"];
  $modelRuta->activar($id);          
  echo $id;
?>