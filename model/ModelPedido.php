<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelPedido
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function verificarFolio($folio_nota)
	{
		$sql = $this->base_datos->query("SELECT count(*) as folios 
		from pedidos where folio_venta='$folio_nota'")->fetchAll();
		return $sql;
	}

	function insertar(
		$fecha,
		$hora,
		$clienteId,
		$clienteNombre,
		$tipoContacto,
		$direccion,
		$colonia,
		$telefono,
		$zonaId,
		$rutaId,
		$vendedorId,
		$productoId,
		$comentario = NULL,
		$usuario = NULL
	) {
		$sql = $this->base_datos->insert("pedidos", [
			"fecha_pedido" => $fecha,
			"hora" => $hora,
			"cliente_id" => $clienteId,
			"cliente_nombre" => $clienteNombre,
			"tipo_contacto_pedido_id" => $tipoContacto,
			"direccion" => $direccion,
			"fracc_col" => $colonia,
			"telefono" => $telefono,
			"zona_id" => $zonaId,
			"ruta_id" => $rutaId,
			"vendedor_id" => $vendedorId,
			"producto_id" => $productoId,
			"comentario" => $comentario,
			"usuario" => $usuario,
			"estatus_pedido_id" => 2
		]);
		return $this->base_datos->id();
	}

	function actualizar($id, $fecha, $rutaId, $tipoContacto, $vendedorId, $productoId, $comentario)
	{
		$sql = $this->base_datos->update("pedidos", [
			"fecha_pedido" => $fecha,
			"ruta_id" => $rutaId,
			"tipo_contacto_pedido_id" => $tipoContacto,
			"vendedor_id" => $vendedorId,
			"producto_id" => $productoId,
			"comentario" => $comentario
		], ["idpedido[=]" => $id]);
		return $sql;
	}

	function atender($id, $hora, $folio, $total)
	{
		$sql = $this->base_datos->update("pedidos", [
			"fecha_entrega" => $hora,
			"folio_venta" => $folio,
			"total_kg_lts" => $total,
			"estatus_pedido_id" => 1
		], ["idpedido[=]" => $id]);

		return $sql->rowCount();
	}

	function cancelarPedido($id)
	{
		$this->base_datos->update("pedidos", [
			"estatus_pedido_id" => 3
		], ["idpedido[=]" => $id]);
	}


	function eliminarPorCliente($clienteId)
	{
		$sql = $this->base_datos->delete(
			"pedidos",
			["AND" => [
				"cliente_id[=]" => $clienteId
			]]
		);
		echo $sql->rowCount();
	}

	function listaTiposContacto()
	{
		$sql = $this->base_datos->select("tipos_contacto_pedido", "*");
		return $sql;
	}

	function avisarRuta($id, $hora, $viaInforme)
	{
		$sql = $this->base_datos->update("pedidos", [
			"tipo_aviso_unidad_id" => $viaInforme,
			"fecha_notificacion_unidad" => $hora
		], ["idpedido[=]" => $id]);

		return $sql->rowCount();
	}

	function actualizarPeriodicidadManualClienteId($clienteId, $periodicidadManual)
	{
		$this->base_datos->update("clientes_pedidos", [
			"periodicidad" => $periodicidadManual
		], ["idclientepedido[=]" => $clienteId]);
	}

	function actualizarPeriodicidad($fecha1, $fecha2, $clienteId, $diff)
	{
		$this->base_datos->update("clientes_pedidos", [
			"fecha1" => $fecha2,
			"fecha2" => $fecha1,
			"periodicidad" => $diff
		], ["idclientepedido[=]" => $clienteId]);
	}

	function eliminarPedido($id)
	{
		$this->base_datos->delete("pedidos", ["idpedido[=]" => $id]);
	}

	/* PRÓXIMOS PEDIDOS MANUAL */
	function proximosPedidosManualZonaEntreFechas($zonaId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT clientes_pedidos.*,fecha_ultimo_pedido,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) AS fecha_proximo_pedido,
			p.nombre as producto_nombre
			FROM clientes_pedidos 
			LEFT JOIN productos p ON p.idproducto = clientes_pedidos.producto_id
			WHERE clientes_pedidos.zona_id = '$zonaId' AND 
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) >= '$fechaInicial' 
			AND (DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) <= '$fechaFinal'
			ORDER BY fecha_proximo_pedido DESC")->fetchAll();
		return $sql;
	}

	function proximosPedidosSistemaZonaEntreFechas($zonaId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT clientes_pedidos.*,fecha_ultimo_pedido,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) AS fecha_proximo_pedido,
			p.nombre as producto_nombre
			FROM clientes_pedidos 
			LEFT JOIN productos p ON p.idproducto = clientes_pedidos.producto_id
			WHERE clientes_pedidos.zona_id = '$zonaId'
			AND (DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) >= '$fechaInicial' 
			AND (DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) <= '$fechaFinal' 
			ORDER BY fecha_proximo_pedido DESC")->fetchAll();
		return $sql;
	}

	function proximosPedidosManualTodos()
	{
		$sql = $this->base_datos->query("SELECT *,
		(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) as proxima 
		FROM `clientes_pedidos` 
		LEFT JOIN cliente_direcciones ON clientes_pedidos.idclientepedido = cliente_direcciones.cliente_id
		LEFT JOIN localidades ON cliente_direcciones.localidad_id = localidades.idlocalidad
		WHERE week(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY),5)= week(curdate(),5) 
		&& year(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY))=year(curdate()) 
		ORDER BY localidades.nombre")->fetchAll();
		return $sql;
	}

	function proximosPedidosManualTodosFecha($fecha)
	{
		$sql = $this->base_datos->query("SELECT *,(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) AS proxima 
		FROM `clientes_pedidos` 
		LEFT JOIN cliente_direcciones ON clientes_pedidos.idclientepedido = cliente_direcciones.cliente_id
		LEFT JOIN localidades ON cliente_direcciones.localidad_id = localidades.idlocalidad
		WHERE week(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY),5)= week(curdate(),5) 
		AND year(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY))=year(curdate()) 
		AND DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)='$fecha' ORDER BY localidades.nombre")->fetchAll();
		return $sql;
	}
	/*PRÓXIMOS PEDIDOS MANUAL */

	function listaPedidosPorZonaEntreFechas($fechaInicial, $fechaFinal, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT pedidos.idpedido,fecha_pedido,
			TIME_FORMAT(hora,'%h:%i %p') AS hora_pedido,
			cliente_nombre,direccion,comentario,fracc_col,
			e_vendedor.nombre AS vendedor_nombre,
			tipos_contacto_pedido.idtipocontactopedido AS tipo_contacto_id,
			tipos_contacto_pedido.nombre AS tipo_contacto_nombre,
			pedidos.telefono AS cliente_telefono,pedidos.zona_id,
			rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,rutas.telefono AS ruta_telefono,
			productos.nombre AS producto_nombre,pedidos.producto_id,
			TIME_FORMAT(fecha_notificacion_unidad,'%h:%i %p') AS fecha_notificacion_unidad,
			(SELECT COUNT(idsmsenviado) FROM sms_enviados 
			WHERE sms_enviados.pedido_id = pedidos.idpedido 
			AND sms_enviados.ruta_id = pedidos.ruta_id 
			AND sms_enviados.tipo_direccion_sms_id = 1 
			AND sms_enviados.estatus_sms_id = 2
			AND sms_enviados.modulo_envio_sms_id = 1) 
			AS mensajes_enviados,
			TIME_FORMAT(fecha_entrega,'%h:%i %p') AS fecha_entrega,
			total_kg_lts,folio_venta,
			estatus_pedido_id,cliente_id,
			estatus_pedido.nombre AS estatus_pedido_nombre,
			pedidos.numero_exterior,pedidos.numero_interior,pedidos.calle,
			l.nombre as localidad_nombre,m.nombre as municipio_nombre,e.nombre as estado_nombre
			FROM pedidos
			INNER JOIN estatus_pedido ON pedidos.estatus_pedido_id = estatus_pedido.idestatuspedido
			INNER JOIN productos ON pedidos.producto_id = productos.idproducto
			INNER JOIN tipos_contacto_pedido ON pedidos.tipo_contacto_pedido_id = tipos_contacto_pedido.idtipocontactopedido
			LEFT JOIN rutas ON pedidos.ruta_id = rutas.idruta
			LEFT JOIN empleados e_vendedor ON pedidos.vendedor_id = e_vendedor.idempleado
			LEFT JOIN localidades l ON l.idlocalidad = pedidos.localidad_id
			LEFT JOIN municipios m ON m.idmunicipio = l.municipio_id
			LEFT JOIN estados e ON e.idestado = m.estado_id
			WHERE pedidos.fecha_pedido >='$fechaInicial' 
			AND pedidos.fecha_pedido <='$fechaFinal' 
			AND pedidos.zona_id = '$zonaId' 
			ORDER BY pedidos.idpedido DESC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function listaPedidosCliente($clienteId)
	{
		$sql = $this->base_datos->query("SELECT pedidos.idpedido,fecha_pedido,TIME_FORMAT(hora,'%h:%i %p') AS hora_pedido,
			cliente_nombre,direccion,comentario,fracc_col,
			e_vendedor.nombre AS vendedor_nombre,
			tipos_contacto_pedido.idtipocontactopedido AS tipo_contacto_id,tipos_contacto_pedido.nombre AS tipo_contacto_nombre,
			pedidos.telefono AS cliente_telefono,pedidos.zona_id,pedidos.zona_id,
			rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,rutas.telefono AS ruta_telefono,
			productos.nombre AS producto_nombre,
			TIME_FORMAT(fecha_notificacion_unidad,'%h:%i %p') AS fecha_notificacion_unidad,mensajes_enviados,
			TIME_FORMAT(fecha_entrega,'%h:%i %p') AS fecha_entrega,
			total_kg_lts,folio_venta,
			estatus_pedido_id,cliente_id,
			estatus_pedido.nombre AS estatus_pedido_nombre
			FROM pedidos
			INNER JOIN estatus_pedido ON pedidos.estatus_pedido_id = estatus_pedido.idestatuspedido
			INNER JOIN rutas ON pedidos.ruta_id = rutas.idruta
			INNER JOIN productos ON pedidos.producto_id = productos.idproducto
			LEFT JOIN tipos_contacto_pedido ON pedidos.tipo_contacto_pedido_id = tipos_contacto_pedido.idtipocontactopedido
			LEFT JOIN empleados e_vendedor ON pedidos.vendedor_id = e_vendedor.idempleado
			WHERE pedidos.cliente_id ='$clienteId'
			ORDER BY pedidos.idpedido DESC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerListaProximosPedidosSistemaZona($fechaInicial, $fechaFinal, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM clientes_pedidos 
		WHERE zona_id='$zonaId' 
		AND proximo_pedido_sistema >='$fechaInicial' 
		AND proximo_pedido_sistema <= '$fechaFinal' 
		ORDER BY proximo_pedido_sistema DESC,idclientepedido ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPorId($id)
	{
		$sql = $this->base_datos->get("pedidos", "*", ["idpedido[=]" => $id]);
		return $sql;
	}

	function obtenerUltimos2PedidosPorClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT * FROM pedidos 
		WHERE cliente_id='$clienteId' AND estatus_pedido_id = 1 
		ORDER BY fecha_pedido DESC LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPrimerPedidoPorClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT * FROM pedidos 
		WHERE cliente_id='$clienteId' AND estatus_pedido_id = 1 
		ORDER BY fecha_pedido ASC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerTotalPedidosPorClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT COUNT(idpedido) AS total_pedidos 
		FROM pedidos WHERE cliente_id='$clienteId' AND estatus_pedido_id = 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerTotalesPedidosZonaFecha($fechaInicial, $fechaFinal, $zonaId)
	{
		//TOTALES POR ESTATUS DE PEDIDO/MÉTODO DE CONTACTO DEL CLIENTE
		$sql = $this->base_datos->query("SELECT * FROM (SELECT COUNT(idpedido) AS total_pedidos FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal') AS total_pedidos,

			(SELECT COUNT(idpedido) AS total_atendidos FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 1) AS total_atendidos,

			(SELECT COUNT(idpedido) AS total_programados FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 2) AS total_programados,
			
			(SELECT COUNT(idpedido) AS total_cancelados FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 3) AS total_cancelados,
			
			(SELECT COUNT(idpedido) AS total_contacto_llamadas FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 1) AS total_contacto_llamadas,
			
			(SELECT COUNT(idpedido) AS total_contacto_ruteo FROM pedidos 
				WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 2) AS total_contacto_ruteo,
			
			(SELECT COUNT(idpedido) AS total_contacto_app FROM pedidos 
			WHERE zona_id = '$zonaId' AND fecha_pedido >='$fechaInicial' 
			AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 3) AS total_contacto_app")->fetchAll(PDO::FETCH_ASSOC);
		return reset($sql);
	}

	function obtenerTotalesPedidosCliente($clienteId)
	{
		//TOTALES POR ESTATUS DE PEDIDO/MÉTODO DE CONTACTO DEL CLIENTE
		$sql = $this->base_datos->query("SELECT * FROM (SELECT COUNT(idpedido) AS total_pedidos 
				FROM pedidos 
				WHERE cliente_id = '$clienteId') AS total_pedidos,

			(SELECT COUNT(idpedido) AS total_atendidos FROM pedidos 
				WHERE cliente_id = '$clienteId' AND estatus_pedido_id = 1) AS total_atendidos,

			(SELECT COUNT(idpedido) AS total_programados FROM pedidos 
				WHERE cliente_id = '$clienteId' AND estatus_pedido_id = 2) AS total_programados,
			
			(SELECT COUNT(idpedido) AS total_cancelados FROM pedidos 
				WHERE cliente_id = '$clienteId' AND estatus_pedido_id = 3) AS total_cancelados,
			
			(SELECT COUNT(idpedido) AS total_contacto_llamadas FROM pedidos 
				WHERE cliente_id = '$clienteId' AND tipo_contacto_pedido_id = 1) AS total_contacto_llamadas,
			
			(SELECT COUNT(idpedido) AS total_contacto_ruteo FROM pedidos 
				WHERE cliente_id = '$clienteId' AND tipo_contacto_pedido_id = 2) AS total_contacto_ruteo,
				
			(SELECT COUNT(idpedido) AS total_contacto_app FROM pedidos 
			WHERE cliente_id = '$clienteId' AND tipo_contacto_pedido_id = 3) AS total_contacto_app")->fetchAll(PDO::FETCH_ASSOC);
		return reset($sql);
	}

	function obtenerTotalesPedidosClienteFechas($fechaInicial, $fechaFinal, $clienteId)
	{
		//TOTALES POR ESTATUS DE PEDIDO/MÉTODO DE CONTACTO DEL CLIENTE
		$sql = $this->base_datos->query("SELECT * FROM (SELECT COUNT(idpedido) AS total_pedidos 
				FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal') AS total_pedidos,

			(SELECT COUNT(idpedido) AS total_atendidos FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 1) AS total_atendidos,

			(SELECT COUNT(idpedido) AS total_programados FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 2) AS total_programados,
			
			(SELECT COUNT(idpedido) AS total_cancelados FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND estatus_pedido_id = 3) AS total_cancelados,
			
			(SELECT COUNT(idpedido) AS total_contacto_llamadas FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 1) AS total_contacto_llamadas,
			
			(SELECT COUNT(idpedido) AS total_contacto_ruteo FROM pedidos 
				WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
				AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 2) AS total_contacto_ruteo,
				
			(SELECT COUNT(idpedido) AS total_contacto_app FROM pedidos 
			WHERE cliente_id = '$clienteId' AND fecha_pedido >='$fechaInicial' 
			AND fecha_pedido <='$fechaFinal' AND tipo_contacto_pedido_id = 3) AS total_contacto_app")->fetchAll(PDO::FETCH_ASSOC);
		return reset($sql);
	}

	function obtenerUltimoPedidoClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT * FROM pedidos WHERE cliente_id='$clienteId' 
		AND estatus_pedido_id = 1 
		ORDER BY fecha_pedido DESC,idpedido DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerProximoPedidoClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT * FROM proximos_pedidos 
		WHERE cliente_id='$clienteId' 
		ORDER BY fecha DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPedidosAnterioresClienteNombre($clienteId, $clienteNombre)
	{
		$sql = $this->base_datos->query("SELECT * FROM pedidos 
		WHERE cliente_nombre='$clienteNombre' AND estatus_pedido_id = 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPedidosAnterioresClienteId($clienteId)
	{
		$sql = $this->base_datos->query("SELECT * FROM pedidos 
		WHERE cliente_id='$clienteId' AND estatus_pedido_id = 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	/*-------------------------TRANSACCIONES PUNTOS (APP)-------------------------- */

	
	function listaTiposTransacciones()
	{
		$sql = $this->base_datos->select("tipos_transaccion_puntos", "*");
		return $sql;
	}

	function obtenerTransaccionesPuntosZona($zonaId,$tipoTransaccionId,$fechaInicial,$fechaFinal)
	{
		//Muestra las transacciones de puntos en pedidos mientras el pedido no estén cancelados
		$sql = $this->base_datos->query("SELECT p.* FROM pedidos p
		INNER JOIN clientes_pedidos cp ON cp.idclientepedido = p.cliente_id
		AND cp.usuario_id IS NOT NULL
		WHERE p.zona_id='$zonaId' 
		AND p.tipo_transaccion_id = '$tipoTransaccionId'
		AND estatus_pedido_id = 1
		AND fecha_pedido >= '$fechaInicial AND fecha_pedido <= '$fechaFinal'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
}
