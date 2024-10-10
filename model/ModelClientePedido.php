<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->baseDatos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->baseDatos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->baseDatos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->baseDatos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelClientePedido
{
	var $baseDatos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD
	var $tabla = "cliente_pedidos";
	function __construct()
	{ //Constructor de la conexion a la BD
		$this->baseDatos = new Medoo();
	}

	function obtenerClientesPedidosZonaId($zonaId)
	{
		$sql = $this->baseDatos->query("SELECT * FROM clientes_pedidos cp
		  	WHERE cp.estatus=1
			AND usuario_id IS NULL
			AND cp.zona_id = '$zonaId'
			order by cp.idclientepedido")->fetchAll();
		return $sql;
	}

	function totalPorZona($zonaId)
	{
		$sql = $this->baseDatos->query("SELECT COUNT(cp.idclientepedido) AS total
			FROM clientes_pedidos cp
			WHERE cp.zona_id = '$zonaId' AND usuario_id IS NULL ")->fetchAll();
		return reset($sql);
	}

	function listaPorZona($zonaId)
	{
		$sql = $this->baseDatos->query("SELECT *,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) AS fecha_proximo_pedido_manual,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) AS fecha_proximo_pedido_sistema
			FROM clientes_pedidos
			WHERE clientes_pedidos.zona_id = '$zonaId' AND usuario_id IS NULL
			ORDER BY nombre")->fetchAll();
		return $sql;
	}

	function listaPorZonaLimite($zonaId, $limiteInicial, $limiteFinal)
	{
		$sql = $this->baseDatos->query("SELECT clientes_pedidos.*,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) AS fecha_proximo_pedido_manual,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) AS fecha_proximo_pedido_sistema
			FROM clientes_pedidos
			WHERE clientes_pedidos.zona_id = '$zonaId' AND usuario_id IS NULL
			ORDER BY clientes_pedidos.nombre 
			LIMIT $limiteInicial,$limiteFinal")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function totalPorZonaFiltrado($zonaId, $valor)
	{
		$sql = $this->baseDatos->query("SELECT COUNT(idclientepedido) AS total
			FROM clientes_pedidos 
			WHERE clientes_pedidos.zona_id = '$zonaId' AND usuario_id IS NULL
			AND (nombre LIKE '%$valor%' OR direccion LIKE '%$valor%' OR telefono LIKE '%$valor%')")->fetchAll();
		return reset($sql);
	}

	function listaPorZonaFiltradoLimite($zonaId, $valor, $limiteInicial, $limiteFinal)
	{
		$sql = $this->baseDatos->query("SELECT *,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad` DAY)) AS fecha_proximo_pedido_manual,
			(DATE_ADD(`fecha_ultimo_pedido`, INTERVAL `periodicidad_sistema` DAY)) AS fecha_proximo_pedido_sistema
			FROM clientes_pedidos
			WHERE (nombre LIKE '%$valor%' OR direccion LIKE '%$valor%' OR telefono LIKE '%$valor%') 
			AND usuario_id IS NULL
			AND clientes_pedidos.zona_id = '$zonaId'
			ORDER BY nombre
			LIMIT $limiteInicial,$limiteFinal")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerClientePorId($clienteId)
	{
		$sql = $this->baseDatos->query("SELECT * FROM clientes_pedidos 
		WHERE idclientepedido = '$clienteId' LIMIT 1")->fetchAll();
		return $sql;
	}

	function obtenerPorId($id)
	{
		$sql = $this->baseDatos->get("clientes_pedidos", "*", ["idclientepedido[=]" => $id]);
		return $sql;
	}

	function obtenerClientePorNombre($clienteNombre)
	{
		$sql = $this->baseDatos->query("SELECT * FROM clientes_pedidos 
		WHERE nombre = '$clienteNombre' LIMIT 1")->fetchAll();
		return $sql;
	}

	function verificarCliente($nombre, $direccion)
	{
		$sql = $this->baseDatos->query("SELECT * FROM clientes_pedidos 
		WHERE nombre='$nombre' AND direccion='$direccion'")->fetchAll();
		return $sql;
	}

	function obtenerPorNombreDireccion($nombre, $direccion)
	{
		$sql = $this->baseDatos->get(
			"clientes_pedidos",
			"*",
			["AND" => [
				"nombre[=]" => $nombre,
				"direccion" => $direccion
			]]
		);
		return $sql;
	}
	public function buscarClientesPorZona($zonaId) {
		return $this->baseDatos->select("clientes_pedidos", [
			"zona_id",
			"nombre"
		], [
			"zona_id" => $zonaId // Filtrar únicamente por zona
		]);
	}
	
	
	function insertar($nombre, $direccion, $colonia, $telefono, $zonaId, $referencias = NULL)
	{
		$sql = $this->baseDatos->insert("clientes_pedidos", [
			"nombre" => $nombre,
			"direccion" => $direccion,
			"colonia" => $colonia,
			"telefono" => $telefono,
			"zona_id" => $zonaId,
			"referencias" => $referencias
		]);
		return $this->baseDatos->id();
	}

	function actualizarPeriodicidadSistemaClienteId($clienteId, $periodicidadSistema, $fechaUltimoPedido)
	{
		$this->baseDatos->update("clientes_pedidos", [
			"periodicidad_sistema" => $periodicidadSistema,
			"fecha_ultimo_pedido" => $fechaUltimoPedido
		], ["idclientepedido[=]" => $clienteId]);
	}

	function desactivar($id)
	{
		$this->baseDatos->update("clientes_pedidos", [
			"estatus" => 0
		], ["idclientepedido[=]" => $id]);
	}

	function activar($id)
	{
		$this->baseDatos->update("clientes_pedidos", [
			"estatus" => 1
		], ["idclientepedido[=]" => $id]);
	}

	function eliminar($id)
	{
		$sql = $this->baseDatos->delete(
			"clientes_pedidos",
			["AND" => [
				"idclientepedido[=]" => $id
			]]
		);
		return $sql->rowCount();
	}

	function actualizarCliente($id, $nombre, $direccion, $telefono, $rfc, $periodicidadManual, $productoId)
	{
		$this->baseDatos->update("clientes_pedidos", [
			"nombre" => $nombre,
			"direccion" => $direccion,
			"telefono" => $telefono,
			"rfc" => $rfc,
			"producto_id" => $productoId,
			"periodicidad" => $periodicidadManual,
		], ["idclientepedido[=]" => $id]);
	}

	function listaPorRuta($rutaId)
	{
		$sql = $this->baseDatos->query("SELECT * FROM clientes_pedidos 
		where ruta_id = $rutaId ORDER BY idclientepedido DESC LIMIT 10")->fetchAll();
		return $sql;
	}

	/**---------------CLIENTE PUNTOS (APP)------------- */

	function listaClientesPuntosZona($zonaId)
	{
		$sql = "SELECT cp.*
		FROM clientes_pedidos cp 
		INNER JOIN usuarios ON usuarios.idusuario = cp.usuario_id ";
		if($zonaId){
			$sql .= " INNER JOIN cliente_direcciones cd ON cp.idclientepedido = cd.cliente_id
			AND cd.zona_id = '$zonaId' ";
		}
		if(!$zonaId){
			$sql .= "WHERE (SELECT count(cd_sq.idclientedireccion) FROM cliente_direcciones cd_sq WHERE cd_sq.cliente_id = cp.idclientepedido) = 0";
		}
		$sql .= " ORDER BY cp.nombre";
		return $this->baseDatos->query($sql)->fetchAll();
		return $sql;
	}

	function listaDireccionesClientePuntos($clienteId)
	{
		$sql = $this->baseDatos->query("SELECT cd.*,
			l.nombre as localidad_nombre,
			m.nombre as municipio_nombre,e.nombre as estado_nombre
			FROM cliente_direcciones cd
			INNER JOIN localidades l ON l.idlocalidad = cd.localidad_id
			INNER JOIN municipios m ON m.idmunicipio = l.municipio_id
			INNER JOIN estados e ON e.idestado = m.estado_id
			WHERE cd.cliente_id = '$clienteId' ")->fetchAll();
		return $sql;
	}

	function listaDireccionesClientePuntosZona($clienteId,$zonaId)
	{
		$sql = $this->baseDatos->query("SELECT cd.*,
			l.nombre as localidad_nombre,
			m.nombre as municipio_nombre,e.nombre as estado_nombre
			FROM cliente_direcciones cd
			INNER JOIN localidades l ON l.idlocalidad = cd.localidad_id
			INNER JOIN municipios m ON m.idmunicipio = l.municipio_id
			INNER JOIN estados e ON e.idestado = m.estado_id
			WHERE cd.zona_id = '$zonaId' AND cd.cliente_id = '$clienteId' ")->fetchAll();
		return $sql;
	}


	function obtenerPuntosPorClienteId($id)
	{
		$sql = $this->baseDatos->query("SELECT c.*,
				(SELECT SUM(tp.cantidad_puntos) FROM transacciones_puntos tp
				WHERE cliente_id = c.id AND tp.tipo_transaccion_puntos = 1) AS total_abonado,
				(SELECT SUM(tp.cantidad_puntos) FROM transacciones_puntos tp
				WHERE cliente_id = c.id AND tp.tipo_transaccion_puntos = 2) AS total_canjeado
		 	FROM $this->tabla c
			WHERE c.id = '$id'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPuntosActuales($clienteId)
	{
		$puntosActuales = 0;
		$cliente = $this->obtenerPorId($clienteId);
		
		if($cliente){
			$totalAbonado = ($cliente["total_abonado"]) ? $cliente["total_abonado"]:0;
			$totalCanjeado = ($cliente["total_canjeado"]) ? $cliente["total_canjeado"]:0;
			$puntosActuales = $totalAbonado-$totalCanjeado;
		}

		return $puntosActuales;
	}
}
