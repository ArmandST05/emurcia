<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelVentaGasolina
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaZonaRutaFecha($zonaId,$rutaId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.clave_ruta AS ruta_nombre, rutas.tipo_ruta_id,detalles_venta.producto_id, 
			productos.nombre AS producto_nombre, productos.capacidad AS producto_capacidad,
			detalles_venta.cantidad, detalles_venta.precio, detalles_venta.total_venta, detalles_venta.cantidad_venta_contado, 
			detalles_venta.descuento_unitario_venta_contado,detalles_venta.descuento_total_venta_contado, detalles_venta.cantidad_venta_credito,
			detalles_venta.descuento_unitario_venta_credito, detalles_venta.descuento_total_venta_credito, 
			detalles_venta.total_venta_credito,detalles_venta.total_venta_contado, detalles_venta.total_rubros_venta,
			detalles_venta.lectura_inicial, detalles_venta.lectura_final, detalles_venta.porcentaje_inicial,detalles_venta.porcentaje_final,
			detalles_venta.entradas_almacen,detalles_venta.otras_salidas, detalles_venta.pruebas, detalles_venta.consumo_interno, detalles_venta.traspasos
			FROM ventas,detalles_venta, productos,rutas
			WHERE ventas.idventa = detalles_venta.venta_id
			AND productos.idproducto = detalles_venta.producto_id
			AND rutas.idruta = ventas.ruta_id
			AND ventas.fecha = '$fecha' 
			AND ventas.ruta_id = '$rutaId' 
			AND ventas.zona_id = '$zonaId'
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function listaZonaRutaProductoFecha($zonaId,$rutaId,$productoId,$fecha)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.clave_ruta AS ruta_nombre, rutas.tipo_ruta_id,detalles_venta.producto_id, 
			productos.nombre AS producto_nombre, productos.capacidad AS producto_capacidad,
			detalles_venta.cantidad, detalles_venta.precio, detalles_venta.total_venta, detalles_venta.cantidad_venta_contado, 
			detalles_venta.descuento_unitario_venta_contado,detalles_venta.descuento_total_venta_contado, detalles_venta.cantidad_venta_credito,
			detalles_venta.descuento_unitario_venta_credito, detalles_venta.descuento_total_venta_credito, 
			detalles_venta.total_venta_credito,detalles_venta.total_venta_contado, detalles_venta.total_rubros_venta,
			detalles_venta.lectura_inicial, detalles_venta.lectura_final, detalles_venta.porcentaje_inicial,detalles_venta.porcentaje_final,
			detalles_venta.entradas_almacen,detalles_venta.otras_salidas, detalles_venta.pruebas, detalles_venta.consumo_interno, detalles_venta.traspasos
			FROM ventas,detalles_venta, productos,rutas
			WHERE ventas.idventa = detalles_venta.venta_id
			AND productos.idproducto = detalles_venta.producto_id
			AND rutas.idruta = ventas.ruta_id
			AND ventas.fecha = '$fecha' 
			AND ventas.ruta_id = '$rutaId' 
			AND ventas.zona_id = '$zonaId'
			AND detalles_venta.producto_id = '$productoId' 
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function obtenerVentaPorId($id)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, detalles_venta.iddetalleventa AS detalle_venta_id,ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.idruta AS ruta_id,
			rutas.tipo_ruta_id,
			rutas.clave_ruta AS ruta_nombre, rutas.tipo_ruta_id,detalles_venta.producto_id, 
			productos.nombre AS producto_nombre, productos.capacidad AS producto_capacidad,
			detalles_venta.cantidad, detalles_venta.precio, detalles_venta.total_venta, detalles_venta.cantidad_venta_contado, 
			detalles_venta.descuento_unitario_venta_contado,detalles_venta.descuento_total_venta_contado, detalles_venta.cantidad_venta_credito,
			detalles_venta.descuento_unitario_venta_credito, detalles_venta.descuento_total_venta_credito, 
			detalles_venta.total_venta_credito,detalles_venta.total_venta_contado, detalles_venta.total_rubros_venta,
			detalles_venta.lectura_inicial, detalles_venta.lectura_final, detalles_venta.porcentaje_inicial,detalles_venta.porcentaje_final,
			detalles_venta.entradas_almacen,detalles_venta.otras_salidas, detalles_venta.pruebas, detalles_venta.consumo_interno, detalles_venta.traspasos
			FROM ventas,detalles_venta, productos,rutas
			WHERE ventas.idventa = detalles_venta.venta_id
			AND productos.idproducto = detalles_venta.producto_id
			AND rutas.idruta = ventas.ruta_id
			AND ventas.idventa = '$id'
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function obtenerTotalVentasPosterioresFecha($ventaId,$rutaId,$fecha)
	{
		$sql = $this->base_datos->query("SELECT COUNT(idventa) AS total_ventas 
		FROM ventas WHERE fecha > '$fecha' AND ruta_id = '$rutaId' AND id != '$ventaId'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerRubrosPorVentaId($id)
	{
		$sql = $this->base_datos->query("SELECT rubros_venta.idrubroventa,
			rubros_venta.nombre,detalle_venta_rubros.cantidad
			FROM rubros_venta 
			LEFT JOIN detalle_venta_rubros 
			ON rubros_venta.idrubroventa = detalle_venta_rubros.rubro_id
			AND detalle_venta_rubros.detalle_venta_id = (SELECT iddetalleventa FROM detalles_venta WHERE venta_id = '$id' LIMIT 1)")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function rutasVentasProductoEntreFechas($zonaId, $productoId, $fechaInicial, $fechaFinal)
	{

		$sql = $this->base_datos->query("SELECT rutas.clave_ruta, rutas.idruta,rutas.tipo_ruta_id
		FROM ventas,rutas,detalles_venta
		WHERE ventas.idventa = detalles_venta.venta_id
		AND rutas.idruta = ventas.ruta_id
		AND detalles_venta.producto_id = '$productoId'
		AND ventas.zona_id = '$zonaId'
		AND ventas.fecha >= '$fechaInicial' 
		AND ventas.fecha <= '$fechaFinal' 
		GROUP BY rutas.idruta ORDER BY rutas.clave_ruta")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function rutasVentasEntreFechas($zonaId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT rutas.clave_ruta, rutas.idruta,rutas.tipo_ruta_id
		FROM ventas,rutas
		WHERE rutas.idruta = ventas.ruta_id
		AND ventas.zona_id = '$zonaId'
		AND ventas.fecha >= '$fechaInicial' 
		AND ventas.fecha <= '$fechaFinal'
		GROUP BY rutas.idruta ORDER BY rutas.clave_ruta")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function fechasVentasRuta($rutaId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT ventas.fecha
		FROM ventas
		WHERE ventas.ruta_id = '$rutaId'
		AND ventas.fecha >= '$fechaInicial' 
		AND ventas.fecha <= '$fechaFinal'
		GROUP BY ventas.fecha ORDER BY ventas.fecha")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function fechasVentasRutaProducto($rutaId, $productoId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT ventas.fecha
		FROM ventas,detalles_venta
		WHERE ventas.idventa = detalles_venta.venta_id
		AND detalles_venta.producto_id = '$productoId'
		AND ventas.ruta_id = '$rutaId'
		AND ventas.fecha >= '$fechaInicial' 
		AND ventas.fecha <= '$fechaFinal' 
		GROUP BY ventas.fecha ORDER BY ventas.fecha")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function totalesVentaRutaFecha($rutaId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT  
		SUM(detalles_venta.cantidad) AS cantidad, 
		SUM(detalles_venta.total_venta) AS total_venta, 
		SUM(detalles_venta.descuento_total_venta_contado) AS descuento_total_venta_contado, 
		SUM(detalles_venta.descuento_total_venta_credito) AS descuento_total_venta_credito, 
		SUM(detalles_venta.total_venta_credito) AS total_venta_credito, 
		SUM(detalles_venta.total_venta_contado) AS total_venta_contado,
		SUM(detalles_venta.total_rubros_venta) AS total_rubros_venta, 
		SUM(detalles_venta.cantidad * productos.capacidad) AS cantidad_producto_capacidad
		FROM ventas,detalles_venta, productos 
		WHERE ventas.idventa = detalles_venta.venta_id 
		AND productos.idproducto = detalles_venta.producto_id 
		AND ventas.fecha = '$fecha' 
		AND ventas.ruta_id = '$rutaId'")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function totalesVentaRutaProductoFecha($rutaId, $productoId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT  
		SUM(detalles_venta.cantidad) AS cantidad, 
		SUM(detalles_venta.total_venta) AS total_venta, 
		SUM(detalles_venta.descuento_total_venta_contado) AS descuento_total_venta_contado, 
		SUM(detalles_venta.descuento_total_venta_credito) AS descuento_total_venta_credito, 
		SUM(detalles_venta.total_venta_credito) AS total_venta_credito, 
		SUM(detalles_venta.total_venta_contado) AS total_venta_contado,
		SUM(detalles_venta.total_rubros_venta) AS total_rubros_venta, 
		SUM(detalles_venta.cantidad * productos.capacidad) AS cantidad_producto_capacidad
		FROM ventas,detalles_venta, productos 
		WHERE ventas.idventa = detalles_venta.venta_id 
		AND productos.idproducto = detalles_venta.producto_id 
		AND detalles_venta.producto_id = '$productoId'
		AND ventas.fecha = '$fecha' 
		AND ventas.ruta_id = '$rutaId'")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function getUltimaVentaZona($fecha, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM ventas 
		WHERE fecha = '$fecha' AND zona_id = $zonaId ORDER BY idventa DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertarVenta($fecha, $hora, $zonaVta, $rutaVta)
	{
		$this->base_datos->insert("ventas", [
			"fecha" => $fecha,
			"hora" => $hora,
			"zona_id" => $zonaVta,
			"ruta_id" => $rutaVta,
			"total" => 0,
		]);
		return $this->base_datos->id();
	}

	function getUltimoDetalleVenta($ventaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM detalles_venta 
		WHERE venta_id = '$ventaId' LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertarDetalleVenta(
		$ventaId,
		$productoVta,

		$cantidadVta,
		$cantidadVtaCredito,
		$precioVta,

		$totalVta,
		$totalVtaCredito,
		$totalVtaContado,

		$pruebasVta
	) {
		$this->base_datos->insert("detalles_venta", [
			"venta_id" => $ventaId,
			"producto_id" => $productoVta,

			"cantidad" => $cantidadVta,
			"cantidad_venta_credito" => $cantidadVtaCredito,//En ventas gas se guarda la cantidad de descuento de crédito
			"precio" => $precioVta,

			"total_venta" => $totalVta,
			"total_venta_credito" => $totalVtaCredito,
			"total_venta_contado" => $totalVtaContado,

			"pruebas" => $pruebasVta,
		]);
		return $this->base_datos->id();
	}

	function actualizarDetalleVenta(
		$detalleVtaId,

		$cantidadVta,
		$cantidadVtaCredito,
		$precioVta,

		$totalVta,
		$totalVtaCredito,
		$totalVtaContado,

		$pruebasVta)
	{
		$this->base_datos->update("detalles_venta", [
			"cantidad" => $cantidadVta,
			"cantidad_venta_credito" => $cantidadVtaCredito,//En ventas gas se guarda la cantidad de descuento de crédito
			"precio" => $precioVta,

			"total_venta" => $totalVta,
			"total_venta_credito" => $totalVtaCredito,
			"total_venta_contado" => $totalVtaContado,

			"pruebas" => $pruebasVta

		], ["iddetalleventa[=]" => $detalleVtaId]);
	}

	function actualizarFechaVenta($ventaId,$fecha)
	{
		$sql = $this->base_datos->update("ventas", ["fecha" => $fecha], ["idventa[=]" => $ventaId]);
		return $sql;
	}

	function eliminar($id)
	{
	  $this->base_datos->delete("ventas", ["idventa[=]" => $id]);
	}
}
