<?php
  include('../../model/ModelRuta.php'); 
  $modelRuta = new ModelRuta();

  $id = $_POST["id"];
  $modelRuta->desactivar($id);     
  echo $id;
?>