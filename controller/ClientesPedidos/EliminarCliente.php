<?php
  include('../../model/ModelClientePedido.php'); 
  $modelCliente = new ModelClientePedido();
  include('../../model/ModelPedido.php'); 
  $modelPedido = new ModelPedido();
  //Eliminación definitiva del cliente y sus pedidos
  $id = $_POST["id"];
  $eliminar = $modelCliente->eliminar($id);  
  if($eliminar){
    $eliminarPedidos = $modelPedido->eliminarPorCliente($id);  
  }   
  echo $id;
?>