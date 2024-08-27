<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCliente
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerAceites($zona)
	{
		$sql = $this->base_datos->query("SELECT * FROM aceites WHERE zona_id='$zona'")->fetchAll();
		return $sql;
	}

	function verificarId($id)
	{
		$resultado = $this->base_datos->select("clientes_credito", "*", ["num_cliente[=]" => $id]);
		return $resultado;
	}

	function buscarPorId($id)
	{
		$resultado = $this->base_datos->query("SELECT * FROM clientes_credito WHERE num_cliente='$id'")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function obtenerSaldoZonasOtorgados($zonaId)
	{
		$sql = $this->base_datos->query("SELECT SUM(credit_use)as otorgados 
		From clientes_credito  WHERE zona_id='$zonaId'")->fetchAll();
		return $sql;
	}

	function obtenerClientes($zona)
	{
		$resultado = $this->base_datos->select("clientes_credito", "*", ["zona_id" => $zona]);
		return $resultado;
	}

	function obtenerPorId($id)
	{
		$sql = $this->base_datos->get("clientes_credito", "*", ["num_cliente" => $id]);
		return $sql;
	}

	function buscarClientesPorNombre($nombre)
	{
		$resultado = $this->base_datos->query("SELECT num_cliente AS id,
			CONCAT(nombre_cliente,' |Nombre comercial: ',nombre_comercial) AS text 
			FROM clientes_credito WHERE (nombre_cliente LIKE '%" . $nombre . "%' OR nombre_comercial LIKE '%" . $nombre . "%') LIMIT 20")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasPorNombre($nombre)
	{
		$resultado = $this->base_datos->query("SELECT num_cliente AS id,
			CONCAT(nombre_cliente,' |Nombre comercial: ',nombre_comercial) AS text 
			FROM clientes_credito,zonas 
			WHERE clientes_credito.zona_id = zonas.idzona 
			AND zonas.tipo_zona_id=1 
			AND (nombre_cliente LIKE '%" . $nombre . "%' OR nombre_comercial LIKE '%" . $nombre . "%') LIMIT 100")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasPorFactura($factura)
	{
		$resultado = $this->base_datos->query("SELECT DISTINCT num_factura AS id,
			CONCAT(num_factura,' |Nombre cliente: ',nombre_cliente) AS text 
			FROM clientes_credito,zonas,creditos_gas 
			WHERE clientes_credito.zona_id = zonas.idzona 
			AND zonas.tipo_zona_id = 1 
			AND clientes_credito.num_cliente = creditos_gas.id_cliente 
			AND (num_factura LIKE '" . $factura . "%') LIMIT 100")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasolinaPorNombre($nombre)
	{
		$resultado = $this->base_datos->query("SELECT num_cliente AS id,
			CONCAT(nombre_cliente,' |Nombre comercial: ',nombre_comercial) AS text 
			FROM clientes_credito,zonas 
			WHERE clientes_credito.zona_id = zonas.idzona 
			AND zonas.tipo_zona_id = 2 
			AND (nombre_cliente LIKE '%" . $nombre . "%' OR nombre_comercial LIKE '%" . $nombre . "%')")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasolinaPorFactura($factura)
	{
		$resultado = $this->base_datos->query("SELECT DISTINCT num_factura AS id,
			CONCAT(num_factura,' |Nombre cliente: ',nombre_cliente) AS text 
			FROM clientes_credito,zonas,creditos_gasolina 
			WHERE clientes_credito.zona_id = zonas.idzona 
			AND zonas.tipo_zona_id = 2
			AND clientes_credito.num_cliente = creditos_gasolina.id_cliente 
			AND (num_factura LIKE '" . $factura . "%') LIMIT 15")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesPorZonaNombre($nombre, $zonaId)
	{
		$resultado = $this->base_datos->query("SELECT num_cliente AS id,
			CONCAT(nombre_cliente,' |Nombre comercial: ',nombre_comercial) AS text 
			FROM clientes_credito 
			WHERE (nombre_cliente LIKE '%" . $nombre . "%' OR nombre_comercial LIKE '%" . $nombre . "%') 
			AND zona_id='" . $zonaId . "'")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasPorZonaFactura($factura, $zonaId)
	{
		$resultado = $this->base_datos->query("SELECT num_factura AS id,
			CONCAT(num_factura,' |Nombre comercial: ',nombre_cliente) AS text 
			FROM clientes_credito,creditos_gas 
			WHERE num_factura LIKE '" . $factura . "%' 
			AND clientes_credito.num_cliente = creditos_gas.id_cliente 
			AND creditos_gas.zona_id='" . $zonaId . "'")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function buscarClientesGasolinaPorZonaFactura($factura, $zonaId)
	{
		$resultado = $this->base_datos->query("SELECT num_factura AS id,
			CONCAT(num_factura,' |Nombre comercial: ',nombre_cliente) AS text 
			FROM clientes_credito,creditos_gasolina 
			WHERE (num_factura LIKE '" . $factura . "%') 
			AND zona_id='" . $zonaId . "'")
			->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;
	}

	function eliminarcliente($id)
	{
		$this->base_datos->delete("clientes_credito", ["num_cliente[=]" => $id]);
	}

	function actualizarCliente($id, $nombrecliente, $domicilio, $colonia, $tiponeg, $credito, $nuevodisp, $pre)
	{
		$this->base_datos->update("clientes_credito", [
			"nombre_cliente" => $nombrecliente,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"nombre_comercial" => $tiponeg,
			"credit_otor" => $credito,
			"credit_actual" => $nuevodisp,
			"precio_des" => $pre
		], ["num_cliente[=]" => $id]);
	}

	function insertar($nombrecliente, $domicilio, $colonia, $tiponeg, $credito, $zonaId, $pre)
	{
		$this->base_datos->insert("clientes_credito", [
			"nombre_cliente" => $nombrecliente,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"nombre_comercial" => $tiponeg,
			"credit_otor" => $credito,
			"credit_actual" => $credito,
			"zona_id" => $zonaId,
			"precio_des" => $pre
		]);
		return $this->base_datos->id();
	}

	function verificarCliente($nombre, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM clientes_credito 
		where nombre_cliente='" . $nombre . "' AND `zona_id`='" . $zonaId . "' ")->fetchAll();
		return $sql;
	}

}
