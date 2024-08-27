<?php
	include('../../model/ModelPedido.php');	
    $modelPedido = new ModelPedido();
    include('../../model/ModelRuta.php');	
	$modelRuta = new ModelRuta();
	
	$id = $_POST['pedidoId'];
    $rutaId = $_POST['ruta'];
    $tipoContacto = $_POST['tipoContacto'];
    $productoId = $_POST['producto'];
    $comentario = strtoupper($_POST['comentario']);
    $fecha = $_POST['fecha']; 
    
    $vendedor = $modelRuta->obtenerVendedorPrincipalPorRuta($rutaId);
    if($vendedor){
        $vendedorId = $vendedor["idempleado"];
    }else{
        $vendedorId = null;
    }

    $actualizar = $modelPedido->actualizar($id,$fecha,$rutaId,$tipoContacto,$vendedorId,$productoId,$comentario);

    if(!$actualizar){
       echo "<script>
         alert('Ocurrió un problema al actualizar el pedido');
        window.location.href = '../../view/index.php?action=pedidos/index.php';
        </script>";
    }
    else{
        echo "<script>
        window.location.href = '../../view/index.php?action=pedidos/index.php';
        </script>";
    }
?>
