<?php
include('../../model/ModelPedido.php');	
include('../../model/ModelClientePedido.php');	
$modelPedido = new ModelPedido();
$modelCliente = new ModelClientePedido();
date_default_timezone_set('America/Mexico_City');

$pedidoId = $_GET['pedidoId'];
if($pedidoId) $pedidoActual = $modelPedido->obtenerPorId($pedidoId);

$clienteId = $pedidoActual['cliente_id'];
$cliente = $modelCliente->obtenerClientePorNombre($pedidoActual["cliente_nombre"]);
$cliente = reset($cliente);
$clienteNombre = $cliente["nombre"];
$clienteId = $cliente["idcliente"];

$productoNombre = $pedidoActual["servicio"];

//Calcular próximo pedido
    $pedidosAnteriores = $modelPedido->obtenerPedidosAnterioresClienteNombre($clienteId, $clienteNombre);
    $fechaPedidoAnterior = "";
    $cantidadPedidos = 0;
    $cantidadDias = 0;
    for ($i = 0; $i <= count($pedidosAnteriores); $i++) {
        if ($i == 0) {
            $fechaPedidoAnterior = date_create($pedidosAnteriores[$i]["fecha_pedido"]);
        } else {
            $fechaPedidoActual = date_create($pedidosAnteriores[$i]["fecha_pedido"]);
            $intervalo = date_diff($fechaPedidoAnterior, $fechaPedidoActual)->days;
            $cantidadDias += doubleval($intervalo);
            $cantidadPedidos++;
            $fechaPedidoAnterior = $fechaPedidoActual;
        }
    }
    $promedioDiasPedido = round($cantidadDias / (count($pedidosAnteriores) + 1));
    $fechaActual = date("Y-m-d");
    $fechaProxPedido = date("Y-m-d", strtotime($fecha_actual . "+ " . $promedioDiasPedido . " days"));

    $modelPedido->insertarProximoPedido($fechaProxPedido, $clienteNombre, $clienteId, $productoNombre, $productoId);
    return json_encode($fechaProxPedido);
    //Calcular próximo pedido
