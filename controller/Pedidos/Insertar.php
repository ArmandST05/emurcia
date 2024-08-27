<?php
    include('../../view/session1.php');
    include('../../model/ModelPedido.php');	
    $modelPedido = new ModelPedido();
    include('../../model/ModelClientePedido.php');	
    $modelClientePedido = new ModelClientePedido();
    include('../../model/ModelRuta.php');	
    $modelRuta = new ModelRuta();
    
    date_default_timezone_set('America/Mexico_City');
    
    $clienteId = $_POST['clienteId'];
    $clienteNombre = strtoupper(trim($_POST['clienteNombre']));
    $tipoContacto = ($_POST['tipoContacto']);
    $direccion = strtoupper(trim($_POST['direccion']));
    $colonia = strtoupper(trim($_POST['colonia']));
    $telefono = trim($_POST['telefono']);
    $zona = $_POST['zona'];
    $zonaId = $_POST['zonaId'];
    $rutaId = $_POST['ruta'];
    $productoId = $_POST['producto'];
    $fecha = $_POST['fecha']; 
    $hora = date('H:i:s');
    $comentario = strtoupper(trim($_POST['comentario']));
    $usuario = $_SESSION['user'];//Usuario que registra
    $referencias = null;

    $vendedor = $modelRuta->obtenerVendedorPrincipalPorRuta($rutaId);
    if($vendedor){
        $vendedorId = $vendedor["idempleado"];
    }else{
        $vendedorId = null;
    }

    if($fecha != date("Y-m-d", strtotime('yesterday')) && $fecha !== date("Y-m-d") && $fecha != date("Y-m-d", strtotime('tomorrow'))){
        return http_response_code(500);
    }
    else if(empty($tipoContacto) || empty($direccion) || empty($colonia) || empty($productoId)){
       return http_response_code(500);
    }
    else {
        //Es un cliente nuevo
        if(empty($clienteId)){

            //Si ya existe un cliente con ese nombre y dirección se utiliza
            $clienteExistente = $modelClientePedido->obtenerPorNombreDireccion($clienteNombre,$direccion);
            if(!empty($clienteExistente)){
                $clienteId = $clienteExistente["idclientepedido"];
            }
            else{
            //Si no, se registra uno nuevo
                $clienteId = $modelClientePedido->insertar($clienteNombre,$direccion,$colonia,$telefono,$zonaId,$referencias = NULL);
            }
        }
        
        $insertar = $modelPedido->insertar($fecha,$hora,$clienteId,$clienteNombre,$tipoContacto,$direccion,$colonia,$telefono,$zonaId,$rutaId,$vendedorId,$productoId,$comentario,$usuario);

        if(isset($insertar)) return $insertar;
        else return http_response_code(500);
    }
?>
