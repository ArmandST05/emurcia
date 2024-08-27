<?php
  include('../../model/ModelClientePedido.php'); 
  $modelCliente = new ModelClientePedido();

  $id = $_POST["id"];
  $modelCliente->activar($id);          
  echo $id;
?>