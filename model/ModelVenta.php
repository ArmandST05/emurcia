<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelVenta
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaZonaFecha($zonaId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros 
			WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			rutas.clave_ruta AS ruta_nombre, rutas.capacidad AS ruta_capacidad,rutas.tipo_ruta_id,detalles_venta.producto_id, 
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
			AND ventas.zona_id = '$zonaId'
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function listaZonaRutaFecha($zonaId, $rutaId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.clave_ruta AS ruta_nombre, rutas.capacidad AS ruta_capacidad,rutas.tipo_ruta_id,detalles_venta.producto_id, 
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

	function listaZonaRutaProductoFecha($zonaId, $rutaId, $productoId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.clave_ruta AS ruta_nombre, rutas.capacidad AS ruta_capacidad,rutas.tipo_ruta_id,detalles_venta.producto_id, 
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

	function listaZonaRutaEntreFechas($zonaId, $rutaId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, 
			DATE_FORMAT(ventas.fecha,'%d-%m-%Y') AS fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			rutas.clave_ruta AS ruta_nombre, rutas.capacidad AS ruta_capacidad,
			rutas.tipo_ruta_id,detalles_venta.producto_id, 
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
			AND ventas.fecha >= '$fechaInicial' 
			AND ventas.fecha <= '$fechaFinal' 
			AND ventas.ruta_id = '$rutaId' 
			AND ventas.zona_id = '$zonaId'
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
	function listaZonaRutaProductoEntreFechas($zonaId, $rutaId, $productoId, $fechaInicial, $fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, ventas.ruta_id, 
			DATE_FORMAT(ventas.fecha,'%d-%m-%Y') AS fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			rutas.clave_ruta AS ruta_nombre, rutas.capacidad AS ruta_capacidad,
			rutas.tipo_ruta_id,detalles_venta.producto_id, 
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
			AND ventas.fecha >= '$fechaInicial' 
			AND ventas.fecha <= '$fechaFinal' 
			AND ventas.ruta_id = '$rutaId' 
			AND ventas.zona_id = '$zonaId'
			AND detalles_venta.producto_id = '$productoId' 
			ORDER BY ventas.fecha, ventas.hora DESC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerVentaPorId($id)
	{
		$sql = $this->base_datos->query("SELECT ventas.idventa, 
			detalles_venta.iddetalleventa AS detalle_venta_id,ventas.ruta_id, ventas.fecha, ventas.hora,
			detalles_venta.iddetalleventa AS detalle_venta_id,
			(SELECT SUM(cantidad) FROM detalle_venta_rubros 
			WHERE detalle_venta_rubros.detalle_venta_id = detalles_venta.iddetalleventa) AS sum_rubros_venta,
			(SELECT COUNT(idventa) FROM ventas AS ventas_posteriores WHERE ventas_posteriores.fecha > ventas.fecha AND ventas_posteriores.ruta_id = ventas.ruta_id) AS total_ventas_posteriores,
			rutas.idruta AS ruta_id,rutas.capacidad AS ruta_capacidad,
			rutas.tipo_ruta_id,rutas.zona_id,
			rutas.clave_ruta AS ruta_nombre,detalles_venta.producto_id, 
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

	function obtenerTotalVentasPosterioresFecha($ventaId, $rutaId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT COUNT(idventa) AS total_ventas 
		FROM ventas WHERE fecha > '$fecha' AND ruta_id = '$rutaId' AND idventa != '$ventaId'")->fetchAll(PDO::FETCH_ASSOC);
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

	function obtenerFechasVentasContadoZona($zonaId, $fechaInicial, $fechaFinal)
	{
		//Obtiene las fechas en que se tuvieron ventas al contado en una zona y dentro de un rango de fechas para después obtener los detallados depósito de ese día si tiene.
		//Utilizado en la vista de descuentos-depósito para después calcular el efectivo real.
		$sql = $this->base_datos->query("SELECT ventas.fecha,
			(SELECT SUM(total_venta_contado) FROM detalles_venta,ventas vdv
				WHERE vdv.idventa = detalles_venta.venta_id
				AND vdv.zona_id = '$zonaId' AND vdv.fecha = ventas.fecha) 
				AS total_venta_contado,
			(SELECT COUNT(iddescuentodeposito) FROM descuentos_deposito 
				WHERE descuentos_deposito.fecha = ventas.fecha 
				AND descuentos_deposito.zona_id = '$zonaId') 
				AS cantidad_descuentos_deposito
			FROM ventas 
			WHERE fecha >= '$fechaInicial' 
			AND fecha <= '$fechaFinal' 
			AND zona_id = '$zonaId'
			GROUP BY ventas.fecha")->fetchAll(PDO::FETCH_ASSOC);
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

	function rutasVentasLtsEntreFechas($zonaId, $fechaInicial, $fechaFinal)
	{
		//Obtener rutas que tuvieron ventas de litros entre fechas
		//Se utiliza en el reporte de Ventas F (Comparar la venta de las lecturas y la venta de porcentajes)
		$sql = $this->base_datos->query("SELECT rutas.clave_ruta, rutas.idruta,rutas.tipo_ruta_id
			FROM ventas,rutas
			WHERE rutas.idruta = ventas.ruta_id
			AND ventas.zona_id = '$zonaId'
			AND ventas.fecha >= '$fechaInicial' 
			AND ventas.fecha <= '$fechaFinal'
			AND (rutas.tipo_ruta_id = 1 OR rutas.tipo_ruta_id = 4 OR rutas.tipo_ruta_id = 5)
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

	//Obtiene los totales vendidos de todas las zonas en ciertas fechas
	function listaVentasProductoFecha($productoId, $fechaInicial, $fechaFinal)
	{
		$sqlQuery =  "SELECT rutas.tipo_ruta_id,detalles_venta.producto_id,productos.capacidad AS producto_capacidad,
			detalles_venta.precio,
			detalles_venta.cantidad, detalles_venta.total_venta, detalles_venta.cantidad_venta_contado, 
			detalles_venta.descuento_total_venta_contado, detalles_venta.cantidad_venta_credito,
			detalles_venta.descuento_total_venta_credito, 
			detalles_venta.total_venta_credito,detalles_venta.total_venta_contado, detalles_venta.total_rubros_venta
			FROM ventas,detalles_venta, productos,rutas
			WHERE ventas.idventa = detalles_venta.venta_id
			AND rutas.idruta = ventas.ruta_id
			AND productos.idproducto = detalles_venta.producto_id
			AND ventas.fecha >= '$fechaInicial' 
			AND ventas.fecha <= '$fechaFinal' ";
		if ($productoId != 0) {
			$sqlQuery .= " AND detalles_venta.producto_id = '$productoId' ";
		}
		$sql = $this->base_datos->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
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

		$lecturaInicialVta,
		$lecturaFinalVta,
		$porcentajeInicialVta,
		$porcentajeFinalVta,

		$cantidadVta,
		$precioVta,

		$cantidadLPVtaContado,
		$descuentoTotalVtaContado,
		$cantidadLPVtaCredito,
		$descuentoTotalVtaCredito,

		$totalVta,
		$totalVtaCredito,
		$totalVtaContado,

		$totalRubrosVta,

		$entradasVta,
		$otrasSalidasVta,
		$pruebasVta,
		$consumoInternoVta,
		$traspasosVta
	) {
		$this->base_datos->insert("detalles_venta", [
			"venta_id" => $ventaId,
			"producto_id" => $productoVta,
			"cantidad" => $cantidadVta,
			"precio" => $precioVta,
			"total_venta" => $totalVta,
			"cantidad_venta_contado" => $cantidadLPVtaContado,
			"descuento_total_venta_contado" => $descuentoTotalVtaContado,
			"cantidad_venta_credito" => $cantidadLPVtaCredito,
			"descuento_total_venta_credito" => $descuentoTotalVtaCredito,
			"total_venta_credito" => $totalVtaCredito,
			"total_venta_contado" => $totalVtaContado,
			"total_rubros_venta" => $totalRubrosVta,

			"lectura_inicial" => $lecturaInicialVta,
			"lectura_final" => $lecturaFinalVta,
			"porcentaje_inicial" => $porcentajeInicialVta,
			"porcentaje_final" => $porcentajeFinalVta,

			"entradas_almacen" => $entradasVta,
			"otras_salidas" => $otrasSalidasVta,
			"pruebas" => $pruebasVta,
			"consumo_interno" => $consumoInternoVta,
			"traspasos" => $traspasosVta,
		]);
		return $this->base_datos->id();
	}

	function actualizarDetalleVenta(
		$detalleVtaId,

		$lecturaInicialVta,
		$lecturaFinalVta,
		$porcentajeInicialVta,
		$porcentajeFinalVta,

		$cantidadVta,
		$precioVta,

		$cantidadLPVtaContado,
		$descuentoTotalVtaContado,
		$cantidadLPVtaCredito,
		$descuentoTotalVtaCredito,

		$totalVta,
		$totalVtaCredito,
		$totalVtaContado,

		$totalRubrosVta,

		$entradasVta,
		$otrasSalidasVta,
		$pruebasVta,
		$consumoInternoVta,
		$traspasosVta
	) {
		$this->base_datos->update("detalles_venta", [
			"cantidad" => $cantidadVta,
			"precio" => $precioVta,
			"total_venta" => $totalVta,
			"cantidad_venta_contado" => $cantidadLPVtaContado,
			"descuento_total_venta_contado" => $descuentoTotalVtaContado,
			"cantidad_venta_credito" => $cantidadLPVtaCredito,
			"descuento_total_venta_credito" => $descuentoTotalVtaCredito,
			"total_venta_credito" => $totalVtaCredito,
			"total_venta_contado" => $totalVtaContado,
			"total_rubros_venta" => $totalRubrosVta,

			"lectura_inicial" => $lecturaInicialVta,
			"lectura_final" => $lecturaFinalVta,
			"porcentaje_inicial" => $porcentajeInicialVta,
			"porcentaje_final" => $porcentajeFinalVta,

			"entradas_almacen" => $entradasVta,
			"otras_salidas" => $otrasSalidasVta,
			"pruebas" => $pruebasVta,
			"consumo_interno" => $consumoInternoVta,
			"traspasos" => $traspasosVta

		], ["iddetalleventa[=]" => $detalleVtaId]);
	}
// Obtener clientes de la tabla clientes_descuento (ventas)
function obtenerClientesVentas($zonaId) {
    return $this->base_datos->select("clientes_descuento", "*", [
        "zona_id" => $zonaId
    ]);
}

// Obtener clientes de la tabla clientes_pedidos (pedidos)
function obtenerClientesPedidos($zonaId) {
    return $this->base_datos->select("clientes_pedidos", "*", [
        "zona_id" => $zonaId
    ]);
}
	
	
	function obtenerProductosPorTipo() {
		return $this->base_datos->select("detalles_venta", [
			"[>]productos" => ["producto_id" => "idproducto"] // Hacemos JOIN con la tabla productos usando producto_id
		], [
			"productos.nombre" // Obtenemos el nombre del producto
		]);
	}
	
	function obtenerVentasPorCliente($clienteId) {
		return $this->base_datos->select("venta_cliente_descuentos", [
			"[>]clientes_descuento" => ["cliente_descuento_id" => "idclientedescuento"],
			"[>]detalles_venta" => ["detalle_venta_id" => "iddetalleventa"],
			"[>]ventas" => ["detalles_venta.venta_id" => "idventa"],
			"[>]productos" => ["detalles_venta.producto_id" => "idproducto"] // Unión con la tabla de productos
		], [
			"venta_cliente_descuentos.idventaclientedescuento",
			"venta_cliente_descuentos.detalle_venta_id",
			"venta_cliente_descuentos.cliente_descuento_id",
			"venta_cliente_descuentos.descuento_id",
			"venta_cliente_descuentos.cantidad",
			"venta_cliente_descuentos.total",
			"venta_cliente_descuentos.created_at",
			"venta_cliente_descuentos.updated_at",
			
			"clientes_descuento.nombre AS nombre_cliente",
			"clientes_descuento.giro",
			"clientes_descuento.calle",
			"clientes_descuento.numero",
			"clientes_descuento.colonia",
			"clientes_descuento.municipio",
			"clientes_descuento.zona_id",
			"clientes_descuento.descuento_id AS cliente_descuento_id_descuento",
			"clientes_descuento.estatus",
			"clientes_descuento.created_at AS cliente_created_at",
			"clientes_descuento.updated_at AS cliente_updated_at",
			
			"detalles_venta.iddetalleventa",
			"detalles_venta.venta_id",
			"detalles_venta.cantidad",
			"productos.nombre(nombre_producto)",	 
			"detalles_venta.precio",
			"detalles_venta.total_venta",
			"detalles_venta.total_venta_credito",
			"detalles_venta.descuento_total_venta_contado",
			"detalles_venta.cantidad_venta_contado",
			"detalles_venta.created_at AS detalle_created_at",
			"detalles_venta.updated_at AS detalle_updated_at",
			
			"ventas.idventa",
			"ventas.zona_id AS venta_zona_id",
			"ventas.ruta_id",
			"ventas.fecha",
			"ventas.hora",
			"ventas.total AS venta_total",
			"ventas.created_at AS venta_created_at",
			"ventas.updated_at AS venta_updated_at"
		], [
			"cliente_descuento_id" => $clienteId
		]);
	}
	function obtenerPedidosPorCliente($clienteId, $fechaInicial, $fechaFinal) {
		// Agregar la condición de fechas solo si se proporcionan
		$sql = "
		SELECT
			p.idpedido,
			p.fecha_pedido,
			p.cliente_id,
			p.cliente_nombre,
			z.nombre AS nombre_zona,
			p.total_kg_lts,
			pr.nombre AS nombre_producto
		FROM
			pedidos p
		INNER JOIN
			zonas z ON p.zona_id = z.idzona
		INNER JOIN
			productos pr ON p.producto_id = pr.idproducto
		WHERE
			p.cliente_id = '$clienteId'
		";
	
		// Si ambas fechas están definidas, agregar la condición de rango de fechas
		if (!empty($fechaInicial) && !empty($fechaFinal)) {
			$sql .= " AND p.fecha_pedido BETWEEN '$fechaInicial' AND '$fechaFinal'";
		}
	
		// Ejecutar la consulta
		$result = $this->base_datos->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
	}
	
	
	public function obtenerVentasPorClienteYFechas($clienteId, $fechaInicial, $fechaFinal) {
		return $this->base_datos->select("venta_cliente_descuentos", [
			"[>]detalles_venta" => ["detalle_venta_id" => "iddetalleventa"], // JOIN con la tabla detalles_venta
			"[>]clientes_descuento" => ["cliente_descuento_id" => "idclientedescuento"] // JOIN con la tabla clientes_descuento
		], [
			"venta_cliente_descuentos.idventaclientedescuento", // Obtener el ID de la venta
			"detalles_venta.cantidad", // Obtener la cantidad de detalles de la venta
			"venta_cliente_descuentos.total", // Total de la venta con descuento
			"venta_cliente_descuentos.created_at" // Fecha de creación de la venta
		], [
			"AND" => [
				"cliente_descuento_id" => $clienteId, // Filtrar por cliente
				"venta_cliente_descuentos.created_at[<>]" => [$fechaInicial, $fechaFinal] // Filtrar por rango de fechas
			]
		]);
	}
	


	function actualizarFechaVenta($ventaId, $fecha)
	{
		$sql = $this->base_datos->update("ventas", ["fecha" => $fecha], ["idventa[=]" => $ventaId]);
		return $sql;
	}

	function insertarRubrosDetalleVenta($detalleVentaId, $rubroId, $cantidad)
	{
		$this->base_datos->insert("detalle_venta_rubros", [
			"detalle_venta_id" => $detalleVentaId,
			"rubro_id" => $rubroId,
			"cantidad" => $cantidad,
		]);
		return $this->base_datos->id();
	}

	function eliminarRubrosDetalleVenta($id)
	{
		$sql = $this->base_datos->delete("detalle_venta_rubros", ["detalle_venta_id[=]" => $id]);
		return $sql;
	}

	function eliminar($id)
	{
		$sql = $this->base_datos->delete("ventas", ["idventa[=]" => $id]);
		return $sql->rowCount();
	}

	/*--------VENTA EMPLEADOS */
	function insertarVentaEmpleadoTodos($ventaId, $vendedor1, $vendedor2)
	{
		if ($vendedor1) {
			$this->base_datos->query("INSERT INTO venta_empleados (venta_id, empleado_id, tipo_empleado_id)
			VALUES ($ventaId, $vendedor1, (SELECT tipo_empleado_id FROM empleados WHERE idempleado = $vendedor1))");
		}

		if ($vendedor2) {
			$this->base_datos->query("INSERT INTO venta_empleados (venta_id, empleado_id, tipo_empleado_id, ayudante)
			VALUES ($ventaId, $vendedor2, (SELECT tipo_empleado_id FROM empleados WHERE idempleado = $vendedor2), 1)");
		}
		return true;
	}

	function insertarVentaEmpleado($ventaId, $vendedor, $tipoEmpleadoId, $ayudante)
	{
		$this->base_datos->query("INSERT INTO venta_empleados (venta_id, empleado_id, tipo_empleado_id,ayudante)
		VALUES ($ventaId, $vendedor, $tipoEmpleadoId,$ayudante)");
		return $this->base_datos->id();
	}

	function eliminarVentaEmpleados($ventaId)
	{
		$sql = $this->base_datos->delete("venta_empleados", ["venta_id[=]" => $ventaId]);
		return $sql->rowCount();
	}

	function obtenerVentaEmpleados($ventaId)
	{
		$sql = $this->base_datos->query("SELECT e.* 
			FROM venta_empleados ve
			INNER JOIN empleados e ON e.idempleado = ve.empleado_id
			WHERE ve.venta_id = '$ventaId'
			ORDER BY ve.ayudante ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerVentaEmpleadoPorTipo($ventaId, $ayudante)
	{
		$sql = $this->base_datos->query("SELECT e.* 
				FROM venta_empleados ve
				INNER JOIN empleados e ON e.idempleado = ve.empleado_id
				WHERE ve.venta_id = '$ventaId'
				AND ve.ayudante = $ayudante
				ORDER BY ve.ayudante ASC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		if ($sql) {
			return $sql[0];
		} else {
			return null;
		}
	}

	/*-------------------INVENTARIO TEÓRICO-------------------- */
	function obtenerVentasKgZonaFecha($zonaId,$fechaInicial,$fechaFinal)
	{
		$totalKgZona = 0;
		$rutasVenta = $this->rutasVentasEntreFechas($zonaId, $fechaInicial, $fechaFinal);

		foreach ($rutasVenta as $claveRuta => $ruta){

			$fechas = $this->fechasVentasRuta($ruta["idruta"], $fechaInicial, $fechaFinal);

			$totalLitros = 0;
			$totalKilos = 0;
			$totalCil = 0;
			$totalCredito = 0;
			$totalDescCredito = 0;
			$totalContado = 0;
			$totalLtsDescContado = 0;
			$totalDescContado = 0;
			$totalVenta = 0;
			$totalPrecioLleno = 0;
			$totalLtsCredito = 0;
			$totalLtsContado = 0;
	
			foreach ($fechas as $claveFecha => $fecha){
			  	$fecha = $fecha["fecha"];

				$ventas = $this->listaZonaRutaFecha($zonaId, $ruta["idruta"], $fecha);

				foreach ($ventas as $venta){
					//Venta de litros en pipas (1)/estación carburación (5)/plantas lts (4)
					if ($venta["producto_id"] == 4) {
					$litros = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
					$totalLitros += $litros;

					$kilos = ($litros * .524);
					$totalKilos += $kilos;
					} else {
					//Venta de kg en Cilindreras (2)/Planta Cilindros (3)
					$kilos = ($venta["total_rubros_venta"] * $venta["producto_capacidad"]);
					$totalKilos += $kilos;

					$litros = ($kilos / .524);
					$totalLitros += $litros;
					}
					$cilindros = ($venta["tipo_ruta_id"] == 2 || $venta["tipo_ruta_id"] == 3) ? $venta["total_rubros_venta"] : 0;
					$totalCil += $cilindros;

					$totalKgZona += $kilos;

					//Dividir el total de la venta que fue a crédito entre el precio al que se vendió para obtener los litros a crédito. 
					//Sacarlo el cálculo en base a lo total $ vendido.
					$ltsCredito = ($venta["total_venta_credito"] + $venta["descuento_total_venta_credito"]) / $venta["precio"];
					$ltsContado = ($venta["total_venta_contado"] + $venta["descuento_total_venta_contado"]) / $venta["precio"];

					$totalCredito += $venta["total_venta_credito"];
					$totalDescCredito += $venta["descuento_total_venta_credito"];
					$totalContado += $venta["total_venta_contado"];       
					$totalLtsDescContado += $venta["cantidad_venta_contado"];
					$totalDescContado += $venta["descuento_total_venta_contado"];
					$totalVenta += ($venta["total_venta"] - $venta["descuento_total_venta_credito"] - $venta["descuento_total_venta_contado"]);
					$totalPrecioLleno += $venta["total_venta"];

					$totalLtsCredito += $ltsCredito;
					$totalLtsContado += $ltsContado;

					//Total Venta en Reporte= (Crédito + Contado) o (Precio lleno - descuento crédito - descuento contado)
					//Precio Lleno = Litros por el precio público sin descuento
				}
			}
		}

		$data["totalKgZona"] = $totalKgZona;

		return $data;
	}	
}