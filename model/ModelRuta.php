<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

//Status 0 = INACTIVO // 1 = ACTIVO
class ModelRuta
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	var $idruta;
	var $clave_ruta;

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
		$this->idruta = null;
		$this->clave_ruta = "";
	}

	function zonaIndex($zona)
	{
		$sql = $this->base_datos->query("SELECT * FROM rutas 
		WHERE zona_id = '$zona' ORDER BY clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM rutas 
		WHERE zona_id = '$zonaId' ORDER BY clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerRutaId($rutaId)
	{
		$sql = $this->base_datos->get("rutas", "*", ["idruta[=]" => $rutaId]);
		return $sql;
	}

	function obtenerVendedores($rutaId)
	{
		$sql = $this->base_datos->query("SELECT vendedor1 as vendedor1_id, vendedor2 vendedor2_id,  
		upper(v1.nombre) as vendedor1_nombre, upper(v2.nombre) as vendedor2_nombre 
		FROM rutas r 
		LEFT JOIN empleados as v1 on v1.idempleado=r.vendedor1
		LEFT JOIN empleados as v2 on v2.idempleado =r.vendedor2
		WHERE idruta=$rutaId")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerVendedorPorRuta($rutaId)
	{
		$sql = $this->base_datos->get("rutas", "vendedor", ["idruta[=]" => $rutaId]);
		return $sql;
	}

	function obtenerVendedorPrincipalPorRuta($rutaId)
	{
		$sql = $this->base_datos->query("SELECT e.* 
        FROM rutas r 
        INNER JOIN empleados e ON e.idempleado = r.vendedor1 
		WHERE r.idruta = '$rutaId' LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		if ($sql) {
			return $sql[0];
		} else {
			return null;
		}
	}

	function listaZonasRutas()
	{
		$sql = $this->base_datos->query("SELECT * FROM zonas order by nombre asc")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaRutas()
	{
		$sql = $this->base_datos->query("SELECT idruta,clave_ruta FROM rutas ORDER BY clave_ruta")->fetchAll();
		return $sql;
	}

	function verificarRuta($nombre)
	{
		$sql = $this->base_datos->query("SELECT * FROM rutas where clave_ruta='$nombre'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Insertar nueva ruta
	function insertar($ruta, $vendedor1, $vendedor2, $zonaId, $txtciudad, $txtcolonia, $tipoRuta, $tipoGananciaRutaId, $capacidad, $inventarioMinimo, $telefono)
	{
		$sql = $this->base_datos->insert("rutas", [
			"clave_ruta" => $ruta,
			"calles" => $txtcolonia,
			"vendedor1" => $vendedor1,
			"vendedor2" => $vendedor2,
			"zona_id" => $zonaId,
			"ciudades" => $txtciudad,
			"tipo_ruta_id" => $tipoRuta,
			"tipo_ganancia_ruta_id" => $tipoGananciaRutaId,
			"capacidad" => $capacidad,
			"inventario_minimo" => $inventarioMinimo,
			"telefono" => $telefono
		]);
		return $this->base_datos->id();
	}

	function obtenerUltimasLecturasRutaId($rutaId)
	{
		$sql = $this->base_datos->query("SELECT ventas.fecha, ventas.hora,detalles_venta.lectura_final,detalles_venta.porcentaje_final 
			FROM detalles_venta,ventas 
			WHERE ventas.idventa = detalles_venta.venta_id 
			AND ventas.ruta_id = '$rutaId' 
			ORDER BY ventas.idventa DESC
			LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function inventarioRutas($zonaId)
	{
		$sql = $this->base_datos->query("SELECT rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,
				rutas.capacidad as ruta_capacidad, 
				productos.nombre AS producto_nombre,productos.idproducto AS producto_id, 
				productos.capacidad as producto_capacidad 
				FROM rutas 
				LEFT JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id 
				INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
				WHERE rutas.zona_id = '$zonaId'
				ORDER BY rutas.clave_ruta, productos.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Muestra rutas activas e inactivas por zona
	function listaPorZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT rutas.*,
			companias.nombre AS compania_nombre,tipos_ruta.nombre AS tipo_ruta,
			permisos.cantidad_permisos AS cantidad_permisos
			FROM rutas
			INNER JOIN companias ON rutas.cia = companias.id 
			INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
			LEFT JOIN (SELECT ruta_id,COUNT(idpermisoruta) AS cantidad_permisos 
			FROM permisos_ruta GROUP BY ruta_id) permisos
			ON rutas.idruta = permisos.ruta_id
			WHERE rutas.zona_id = '$zonaId'
			ORDER BY rutas.zona_id,rutas.clave_ruta")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaPorZonaEstatus($zonaId, $estatus)
	{
		$sql = $this->base_datos->query("SELECT rutas.*,
			companias.nombre AS compania_nombre,
			zonas.nombre as zona_nombre,
			tipos_ruta.nombre AS tipo_ruta_nombre,
			permisos.cantidad_permisos AS cantidad_permisos,
			upper(vendedor1.nombre) AS vendedor1_nombre,
			upper(vendedor2.nombre) AS vendedor2_nombre,
			tgr.nombre AS tipo_ganancia_ruta_nombre
			FROM rutas
			INNER JOIN zonas ON zonas.idzona = rutas.zona_id 
			AND zonas.idzona = '$zonaId'
			INNER JOIN companias ON companias.idcompania = zonas.compania_id 
			INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
			INNER JOIN tipos_ganancia_ruta tgr ON tgr.idtipogananciaruta = rutas.tipo_ganancia_ruta_id
			LEFT JOIN (SELECT ruta_id, COUNT(idpermisoruta) AS cantidad_permisos FROM permisos_ruta GROUP BY ruta_id) permisos
			ON rutas.idruta = permisos.ruta_id
			LEFT JOIN empleados AS vendedor1 ON rutas.vendedor1 = vendedor1.idempleado
			LEFT JOIN empleados AS vendedor2 ON rutas.vendedor2 = vendedor2.idempleado
			WHERE rutas.estatus = '$estatus'
			ORDER BY rutas.zona_id, rutas.clave_ruta")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaPorZonaEstatusTipo($zonaId, $estatus, $tipoRuta)
	{
		$sql = $this->base_datos->query("SELECT rutas.*,
			companias.nombre AS compania_nombre,tipos_ruta.nombre AS tipo_ruta,
			permisos.cantidad_permisos AS cantidad_permisos
			FROM rutas
			INNER JOIN zonas ON zonas.idzona = rutas.zona_id 
			AND zonas.idzona = '$zonaId'
			INNER JOIN companias ON companias.idcompania = zonas.compania_id 
			INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
			LEFT JOIN (SELECT ruta_id,COUNT(idpermisoruta) AS cantidad_permisos FROM permisos_ruta GROUP BY ruta_id) permisos
			ON rutas.idruta = permisos.ruta_id
			WHERE rutas.estatus = '$estatus'
			AND rutas.tipo_ruta_id = '$tipoRuta'
			ORDER BY rutas.zona_id,rutas.clave_ruta")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Muestra las rutas inactivas y activas que realizan ventas
	function listaPorZonaVenta($zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM rutas 
		WHERE zona_id = '$zonaId' 
		AND (tipo_ruta_id != 6) 
		ORDER BY clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Muestra las rutas que realizan ventas de acuerdo a su estatus actual
	function listaPorZonaVentaEstatus($zonaId, $estatus)
	{
		$sql = $this->base_datos->query("SELECT * FROM rutas 
		WHERE zona_id = '$zonaId' 
		AND (tipo_ruta_id != 6) 
		AND rutas.estatus = '$estatus'
		ORDER BY clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Muestra rutas activas e inactivas de todas las zonas
	function listaTodas()
	{
		$sql = $this->base_datos->query("SELECT rutas.*,
			companias.nombre AS compania_nombre,tipos_ruta.nombre as tipo_ruta,
			permisos.cantidad_permisos AS cantidad_permisos
			FROM rutas
			INNER JOIN zonas ON zonas.idzona = rutas.zona_id 
			INNER JOIN companias ON companias.idcompania = zonas.compania_id 
			INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
			LEFT JOIN (SELECT ruta_id,COUNT(idpermisoruta) AS cantidad_permisos FROM permisos_ruta GROUP BY ruta_id) permisos
			ON rutas.idruta = permisos.ruta_id
			ORDER BY rutas.zona_id,rutas.clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaTodasEstatus($estatus)
	{
		$sql = $this->base_datos->query("SELECT rutas.*,tipos_ruta.nombre as tipo_ruta_nombre,
			companias.nombre AS compania_nombre,zonas.nombre as zona_nombre,
			tipos_ruta.nombre as tipo_ruta,
			permisos.cantidad_permisos AS cantidad_permisos,
			upper(vendedor1.nombre) AS vendedor1_nombre,
			upper(vendedor2.nombre) AS vendedor2_nombre,
			tgr.nombre AS tipo_ganancia_ruta_nombre
			FROM rutas
			INNER JOIN zonas ON zonas.idzona = rutas.zona_id
			INNER JOIN companias ON companias.idcompania = zonas.compania_id 
			INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
			INNER JOIN tipos_ganancia_ruta tgr ON tgr.idtipogananciaruta = rutas.tipo_ganancia_ruta_id
			LEFT JOIN (SELECT ruta_id,COUNT(idpermisoruta) AS cantidad_permisos FROM permisos_ruta GROUP BY ruta_id) permisos
			ON rutas.idruta = permisos.ruta_id
			LEFT JOIN empleados AS vendedor1 ON rutas.vendedor1 = vendedor1.idempleado
			LEFT JOIN empleados AS vendedor2 ON rutas.vendedor2 = vendedor2.idempleado
			WHERE rutas.estatus = '$estatus'
			ORDER BY rutas.zona_id,rutas.clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Muestra la ruta a la que está asignado el empleado actualmente
	function obtenerRutaActualEmpleado($empleadoId)
	{
		$sql = $this->base_datos->query("SELECT rutas.*,
					companias.nombre AS compania_nombre,tipos_ruta.nombre as tipo_ruta
					FROM rutas
					INNER JOIN zonas ON zonas.idzona = rutas.zona_id 
					INNER JOIN companias ON companias.idcompania = zonas.compania_id 
					INNER JOIN tipos_ruta ON rutas.tipo_ruta_id = tipos_ruta.idtiporuta
					WHERE vendedor1 = $empleadoId OR vendedor2 = $empleadoId
					ORDER BY rutas.zona_id,rutas.clave_ruta LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		if (!empty($sql)) {
			return $sql[0];
		} else {
			return null;
		}
	}

	function actualizar($id, $rutaNombre, $vendedor1, $vendedor2, $zonaId, $listaCiudades, $listaColonias, $tipoRuta, $tipoGananciaRutaId, $capacidad, $inventarioMinimo, $telefono)
	{
		$this->base_datos->update("rutas", [
			"clave_ruta" => $rutaNombre,
			"telefono" => $telefono,
			"calles" => $listaColonias,
			"vendedor1" => $vendedor1,
			"vendedor2" => $vendedor2,
			"zona_id" => $zonaId,
			"ciudades" => $listaCiudades,
			"tipo_ruta_id" => $tipoRuta,
			"tipo_ganancia_ruta_id" => $tipoGananciaRutaId,
			"capacidad" => $capacidad,
			"inventario_minimo" => $inventarioMinimo

		], ["idruta[=]" => $id]);
	}

	function actualizarTelefono($rutaId, $telefono = NULL)
	{
		$this->base_datos->update("rutas", [
			"telefono" => $telefono
		], ["idruta[=]" => $rutaId]);
	}

	function actualizarVendedores($id, $vendedor1, $vendedor2)
	{
		$this->base_datos->update("rutas", [
			"vendedor1" => $vendedor1,
			"vendedor2" => $vendedor2

		], ["idruta[=]" => $id]);
	}


	function eliminarRuta($id)
	{
		$this->base_datos->delete("rutas", ["idruta[=]" => $id]);
	}

	function eliminarClienteRuta($id)
	{
		$this->base_datos->update("clientes_pedidos", [
			"idruta" => 0
		], ["idcliente[=]" => $id]);
	}

	function agregarClienteRuta($id, $ruta)
	{
		$this->base_datos->update("clientes_pedidos", [
			"idruta" => $ruta
		], ["idcliente[=]" => $id]);
	}

	function listaTiposRutaTodos()
	{
		$sql = $this->base_datos->query("SELECT * FROM tipos_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaTiposGananciaRutaTodos()
	{
		$sql = $this->base_datos->query("SELECT * FROM tipos_ganancia_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function activar($id)
	{
		$this->base_datos->update("rutas", [
			"estatus" => 1
		], ["idruta[=]" => $id]);
	}

	function desactivar($id)
	{
		$this->base_datos->update("rutas", [
			"estatus" => 0
		], ["idruta[=]" => $id]);
	}
}
