<?php
  include('../../model/ModelClientePedido.php'); 
  $modelCliente = new ModelClientePedido();

  $id = $_POST["id"];
  $modelCliente->desactivar($id);     
  echo $id;
?>