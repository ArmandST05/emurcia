<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelInventario
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerEntradasRutaProducto($rutaId, $productoId)
	{
		$sql = $this->base_datos->query("SELECT SUM(cantidad) AS cantidad 
		FROM inventario WHERE ruta_id = '$rutaId' 
		AND producto_id = $productoId 
		AND tipo_transaccion_inventario_id = 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerEntradasRutaProductoCorteFecha($rutaId, $productoId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT SUM(cantidad) AS cantidad 
		FROM inventario WHERE ruta_id = '$rutaId' 
		AND producto_id = $productoId 
		AND tipo_transaccion_inventario_id = 1
		AND inventario.fecha <= '$fecha'
		")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerSalidasRutaProducto($rutaId, $productoId)
	{
		$sql = $this->base_datos->query("SELECT SUM(cantidad) AS cantidad 
		FROM inventario WHERE ruta_id = '$rutaId' 
		AND producto_id = $productoId 
		AND tipo_transaccion_inventario_id = 2")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerSalidasRutaProductoCorteFecha($rutaId, $productoId, $fecha)
	{
		$sql = $this->base_datos->query("SELECT SUM(cantidad) AS cantidad 
		FROM inventario WHERE ruta_id = '$rutaId' 
		AND producto_id = $productoId 
		AND tipo_transaccion_inventario_id = 2
		AND inventario.fecha <= '$fecha'
		")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerReporteInventarioFechas($fechaInicial, $fechaFinal, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT calendar.fecha_inventario,rutas.idruta AS ruta_id, 
		rutas.clave_ruta AS ruta_nombre,
		rutas.capacidad as ruta_capacidad, 
		productos.nombre AS producto_nombre,productos.idproducto AS producto_id, 
		productos.capacidad as producto_capacidad,
		ifnull((SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
			AND producto_id = productos.idproducto 
			AND tipo_transaccion_inventario_id = 1
			AND inventario.fecha <= calendar.fecha_inventario),0) AS total_entradas,
		ifnull((SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
			AND producto_id = productos.idproducto 
			AND tipo_transaccion_inventario_id = 2
			AND inventario.fecha <= calendar.fecha_inventario),0) AS total_salidas,
		ifnull(((SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
			AND producto_id = productos.idproducto 
			AND tipo_transaccion_inventario_id = 1
			AND inventario.fecha <= calendar.fecha_inventario) - 
		(SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
			AND producto_id = productos.idproducto 
			AND tipo_transaccion_inventario_id = 2
			AND inventario.fecha <= calendar.fecha_inventario)),0)
		AS inventario_actual
		FROM rutas
		CROSS JOIN (
			SELECT fecha_inventario FROM (
				SELECT adddate('1970-01-010',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) fecha_inventario 
				from            
					(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
					(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
					(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
					(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
					(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4
			) v
			WHERE fecha_inventario BETWEEN '$fechaInicial' AND '$fechaFinal'
		) calendar
		LEFT JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id 
		INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
		WHERE rutas.zona_id = '$zonaId'
		AND rutas.estatus = 1
		ORDER BY calendar.fecha_inventario,rutas.clave_ruta, productos.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
	function obtenerReporteInventarioFechasEstaciones($fechaInicial, $fechaFinal, $rutaId)
	{
		$sql = $this->base_datos->query("SELECT 
    calendar.fecha_inventario, 
    rutas.idruta AS ruta_id, 
    rutas.clave_ruta AS ruta_nombre,
    rutas.capacidad AS ruta_capacidad, 
    productos.nombre AS producto_nombre, 
    productos.idproducto AS producto_id, 
    productos.capacidad AS producto_capacidad,
    IFNULL(SUM(CASE WHEN i.tipo_transaccion_inventario_id = 1 THEN i.cantidad ELSE 0 END), 0) AS total_entradas,
    IFNULL(SUM(CASE WHEN i.tipo_transaccion_inventario_id = 2 THEN i.cantidad ELSE 0 END), 0) AS total_salidas,
    IFNULL(SUM(CASE WHEN i.tipo_transaccion_inventario_id = 1 THEN i.cantidad ELSE 0 END), 0) -
    IFNULL(SUM(CASE WHEN i.tipo_transaccion_inventario_id = 2 THEN i.cantidad ELSE 0 END), 0) AS inventario_actual
FROM 
    rutas
CROSS JOIN (
    SELECT ADDDATE('1970-01-01', t4.i * 10000 + t3.i * 1000 + t2.i * 100 + t1.i * 10 + t0.i) AS fecha_inventario 
    FROM            
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
        (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4
) calendar
LEFT JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id 
INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
LEFT JOIN inventario i ON i.ruta_id = rutas.idruta 
    AND i.producto_id = productos.idproducto 
    AND i.fecha <= calendar.fecha_inventario
WHERE 
    rutas.estatus = 1
    AND rutas.tipo_ruta_id = 5
    AND rutas.idruta = '$rutaId'
    AND calendar.fecha_inventario BETWEEN '$fechaInicial' AND '$fechaFinal'
GROUP BY 
    calendar.fecha_inventario, rutas.idruta, productos.idproducto
ORDER BY 
    calendar.fecha_inventario, rutas.clave_ruta, productos.nombre

		")->fetchAll(PDO::FETCH_ASSOC);
	
		return $sql;
	}
	


	function obtenerReporteInventarioGasolinaFechas($fechaInicial, $fechaFinal, $mes, $anio, $zonaId)
	{
		//Se obtienen TODOS los datos de ventas de la ruta/tanque
		$sql = $this->base_datos->query("SELECT rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,
			rutas.capacidad AS ruta_capacidad, rutas.inventario_minimo AS ruta_inventario_minimo,
			productos.nombre AS producto_nombre,productos.idproducto AS producto_id,
			(SELECT cantidad FROM inventario_inicial_mes 
				WHERE mes = '$mes' 
				AND anio = '$anio'
				AND zona_id = '$zonaId'
				AND rutas.idruta = inventario_inicial_mes.ruta_id
				AND inventario_inicial_mes.producto_id = productos.idproducto) AS inventario_inicial,
			(SELECT SUM(litros) FROM compras_gasolina 	
				WHERE fecha >= '$fechaInicial' 
				AND fecha <= '$fechaFinal'
				AND zona_id = '$zonaId'
				AND producto_id = productos.idproducto) AS total_compras,
			(SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
				AND producto_id = productos.idproducto 
				AND tipo_transaccion_inventario_id = 1
				AND detalle_venta_id IS NOT NULL
				AND inventario.fecha >= '$fechaInicial' AND inventario.fecha <= '$fechaFinal') AS total_entradas,
			(SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
				AND producto_id = productos.idproducto 
				AND tipo_transaccion_inventario_id = 2
				AND inventario.fecha >= '$fechaInicial' AND inventario.fecha <= '$fechaFinal') AS total_salidas,
			(SELECT COUNT(ventas.idventa) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto) AS cantidad_ventas_general,
			(SELECT COUNT(ventas.idventa) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto
				AND ventas.fecha >= '$fechaInicial' 
				AND ventas.fecha <= '$fechaFinal') AS cantidad_ventas_mes,
			(SELECT SUM(cantidad) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto) AS total_ventas_general,
			(SELECT SUM(cantidad) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto
				AND ventas.fecha >= '$fechaInicial' 
				AND ventas.fecha <= '$fechaFinal') AS total_ventas_mes
			FROM rutas
			INNER JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id
			INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
			WHERE rutas.zona_id = '$zonaId'
			AND rutas.estatus = 1
			ORDER BY rutas.clave_ruta ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerReporteInventarioGasolinaRutaFechas($fechaInicial, $fechaFinal, $mes, $anio, $zonaId, $rutaId)
	{
		$sql = $this->base_datos->query("SELECT rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,
			rutas.capacidad AS ruta_capacidad, rutas.inventario_minimo AS ruta_inventario_minimo,
			productos.nombre AS producto_nombre,productos.idproducto AS producto_id,
			(SELECT cantidad FROM inventario_inicial_mes 
				WHERE mes = '$mes' 
				AND anio = '$anio'
				AND zona_id = '$zonaId'
				AND rutas.idruta = inventario_inicial_mes.ruta_id
				AND inventario_inicial_mes.producto_id = productos.idproducto LIMIT 1) AS inventario_inicial,
			(SELECT SUM(litros) FROM compras_gasolina 	
				WHERE fecha >= '$fechaInicial' 
				AND fecha <= '$fechaFinal'
				AND zona_id = '$zonaId'
				AND producto_id = productos.idproducto) AS total_compras,
			(SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
				AND producto_id = productos.idproducto 
				AND tipo_transaccion_inventario_id = 1
				AND detalle_venta_id IS NOT NULL
				AND inventario.fecha >= '$fechaInicial' AND inventario.fecha <= '$fechaFinal') AS total_entradas,
			(SELECT SUM(cantidad) FROM inventario WHERE ruta_id = rutas.idruta 
				AND producto_id = productos.idproducto 
				AND tipo_transaccion_inventario_id = 2
				AND inventario.fecha >= '$fechaInicial' AND inventario.fecha <= '$fechaFinal') AS total_salidas,
			(SELECT COUNT(ventas.idventa) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto
				AND ventas.fecha >= '$fechaInicial' 
				AND ventas.fecha <= '$fechaFinal') AS cantidad_ventas,
			(SELECT SUM(cantidad) FROM detalles_venta,ventas 
				WHERE ventas.idventa = detalles_venta.venta_id
				AND ventas.ruta_id = rutas.idruta 
				AND detalles_venta.producto_id = productos.idproducto
				AND ventas.fecha >= '$fechaInicial' 
				AND ventas.fecha <= '$fechaFinal') AS total_ventas
			FROM rutas
			INNER JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id
			INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
			WHERE rutas.zona_id = '$zonaId'
			AND rutas.estatus = 1
			AND rutas.idruta = '$rutaId'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerInventarioInicialGasolinaZonaMesAnio($zonaId, $mes, $anio)
	{
		$sql = $this->base_datos->query("SELECT rutas.idruta AS ruta_id, rutas.clave_ruta AS ruta_nombre,
				rutas.capacidad AS ruta_capacidad, 
				productos.nombre AS producto_nombre,productos.idproducto AS producto_id, 
				productos.capacidad AS producto_capacidad,
				inventario_inicial_mes.cantidad AS inventario_inicial
				FROM rutas 
				LEFT JOIN tipo_ruta_productos ON rutas.tipo_ruta_id = tipo_ruta_productos.tipo_ruta_id
				LEFT JOIN inventario_inicial_mes ON rutas.idruta = inventario_inicial_mes.ruta_id
					AND inventario_inicial_mes.mes = '$mes' AND inventario_inicial_mes.anio = '$anio'
				INNER JOIN productos ON tipo_ruta_productos.producto_id = productos.idproducto 
				WHERE rutas.zona_id = '$zonaId'
				AND rutas.estatus = 1
				ORDER BY rutas.clave_ruta, productos.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertarEntradaInventario(
		$fecha,
		$rutaId,
		$zonaId,
		$productoId,
		$cantidad,
		$detalle_venta_id = NULL
	) {
		$this->base_datos->insert("inventario", [
			"fecha" => $fecha,
			"ruta_id" => $rutaId,
			"zona_id" => $zonaId,
			"producto_id" => $productoId,
			"cantidad" => $cantidad,
			"detalle_venta_id" => $detalle_venta_id,
			"tipo_transaccion_inventario_id" => 1,
		]);
		return $this->base_datos->id();
	}

	function insertarSalidaInventario(
		$fecha,
		$rutaId,
		$zonaId,
		$productoId,
		$cantidad,
		$detalle_venta_id = null
	) {
		$this->base_datos->insert("inventario", [
			"fecha" => $fecha,
			"ruta_id" => $rutaId,
			"zona_id" => $zonaId,
			"producto_id" => $productoId,
			"cantidad" => $cantidad,
			"detalle_venta_id" => $detalle_venta_id,
			"tipo_transaccion_inventario_id" => 2,
		]);
		return $this->base_datos->id();
	}

	function insertarInventarioInicial(
		$fecha,
		$mes,
		$anio,
		$rutaId,
		$zonaId,
		$productoId,
		$cantidad
	) {
		$this->base_datos->insert("inventario_inicial_mes", [
			"fecha" => $fecha,
			"mes" => $mes,
			"anio" => $anio,
			"ruta_id" => $rutaId,
			"zona_id" => $zonaId,
			"producto_id" => $productoId,
			"cantidad" => $cantidad
		]);
		return $this->base_datos->id();
	}

	function eliminarInventarioDetalleVenta($detalleVtaId)
	{
		$sql = $this->base_datos->delete("inventario", ["detalle_venta_id[=]" => $detalleVtaId]);
		return $sql;
	}


	/*----------------INVENTARIO TEÓRICO-------------------- */

	function obtenerInventarioGasKgZonaFecha($zonaId, $fecha)
	{
		 /*Si el producto es Lts(4) realizar cálculo de inventario en base a inventario actual y capacidad de Pipa
		 $litros = (($inventarioActual * $ruta["ruta_capacidad"]) / 100);
		 $kilos = ($litros * .524);
		
		//En tanques de gasolina
		else if($productoId == 6 || $productoId == 7 || $productoId == 8){
			$litros = $inventarioActual;
			$kilos = ($litros * .524);
		}
		//Se calcula los litros vendidos por el cilindro
		else {
			$kilos = ($inventarioActual * $ruta["producto_capacidad"]);
			$litros = ($kilos / .524);
		}*/
		//Obtener solo los kg
		$sql = $this->base_datos->query("SELECT 
		SUM(
			IF(trp.producto_id = 4,
				(ifnull(
					(
						((SELECT SUM(isq1.cantidad) FROM inventario isq1 WHERE isq1.ruta_id = r.idruta 
						AND isq1.producto_id = p.idproducto AND isq1.tipo_transaccion_inventario_id = 1
						AND isq1.fecha <= '$fecha') - 
						(SELECT SUM(isq2.cantidad) FROM inventario isq2 WHERE isq2.ruta_id = r.idruta 
						AND isq2.producto_id = p.idproducto AND isq2.tipo_transaccion_inventario_id = 2
						AND isq2.fecha <= '$fecha'))
						*r.capacidad/100
					),0)* .524
				),
				if(trp.producto_id = 6 OR trp.producto_id = 7 OR trp.producto_id = 8,
					(ifnull(
						(
							(SELECT SUM(isq1.cantidad) FROM inventario isq1 WHERE isq1.ruta_id = r.idruta 
							AND isq1.producto_id = p.idproducto AND isq1.tipo_transaccion_inventario_id = 1
							AND isq1.fecha <= '$fecha') - 
							(SELECT SUM(isq2.cantidad) FROM inventario isq2 WHERE isq2.ruta_id = r.idruta 
							AND isq2.producto_id = p.idproducto AND isq2.tipo_transaccion_inventario_id = 2
							AND isq2.fecha <= '$fecha')
						),0)* .524
					),
					(ifnull(
						(
							(SELECT SUM(isq1.cantidad) FROM inventario isq1 WHERE isq1.ruta_id = r.idruta 
							AND isq1.producto_id = p.idproducto AND isq1.tipo_transaccion_inventario_id = 1
							AND isq1.fecha <= '$fecha') - 
							(SELECT SUM(isq2.cantidad) FROM inventario isq2 WHERE isq2.ruta_id = r.idruta 
							AND isq2.producto_id = p.idproducto AND isq2.tipo_transaccion_inventario_id = 2
							AND isq2.fecha <= '$fecha')
						),0)* p.capacidad
					)
				)
			)
			
		) AS kgs_actuales
		FROM inventario i
		INNER JOIN rutas r ON r.idruta = i.ruta_id
		LEFT JOIN tipo_ruta_productos trp ON r.tipo_ruta_id = trp.tipo_ruta_id 
		INNER JOIN productos p ON trp.producto_id = p.idproducto 
		WHERE i.zona_id = '$zonaId'
		AND i.fecha <= '$fecha'
		")->fetchAll(PDO::FETCH_OBJ);
		return $sql;
	}

	function obtenerTotalInventarioGasKgZonaFecha($zonaId, $fecha)
	{
		$datosInventario = $this->obtenerReporteInventarioFechas($fecha, $fecha, $zonaId);
		$inventarios = array();
		
		foreach ($datosInventario as $dato) {
		  $inventarios[$dato["fecha_inventario"]][] = $dato;
		}
		$totalKgZona = 0;
		$totalLtsZona = 0;

		foreach ($inventarios as $claveInventario => $inventarioFecha){
			$totalLitrosFecha = 0;
			$totalKilosFecha = 0;

			foreach ($inventarioFecha as $claveRuta => $ruta){

			  $productoId = $ruta["producto_id"];
			  $inventarioActual = number_format($ruta["inventario_actual"], 2);

			  $totalEntradas = $ruta["total_entradas"];
			  $totalSalidas = $ruta["total_salidas"];

			  if (number_format($totalSalidas, 2) == 0) $inventarioActual = number_format($totalEntradas, 2);
			  else $inventarioActual = number_format(($totalEntradas - $totalSalidas), 2);

			  //Si el producto es Lts realizar cálculo de inventario en base a inventario actual y capacidad de Pipa
			  if ($productoId == 4) {
				$litros = (($inventarioActual * $ruta["ruta_capacidad"]) / 100);
				$kilos = ($litros * .524);
			  } 
			  elseif($productoId == 6 || $productoId == 7 || $productoId == 8){
				//Tanques Gasolina
				$litros = $inventarioActual;
				$kilos = ($litros * .524);
			  }
			  else {
				//Se calcula los litros vendidos por el cilindro
				$kilos = ($inventarioActual * $ruta["producto_capacidad"]);
				$litros = ($kilos / .524);
			  }
			  $totalLitrosFecha += $litros;
			  $totalKilosFecha += $kilos;
			  $rutaId = $ruta["ruta_id"];
			}

			$totalKgZona += $totalKilosFecha;
			$totalLtsZona += $totalLitrosFecha;
		}

		$data["totalKgZona"] = $totalKilosFecha;
		$data["totalLtsZona"] = $totalLitrosFecha;

		return $data;
		
	}
function obtenerTotalInventarioGasKgZonaFechaEstaciones($zonaId, $fecha)
{
    $datosInventario = $this->obtenerReporteInventarioFechasEstaciones($fecha, $fecha, $zonaId);
    $inventarios = array();
    
    foreach ($datosInventario as $dato) {
        $inventarios[$dato["fecha_inventario"]][] = $dato;
    }

    $totalKgZona = 0;
    $totalLtsZona = 0;

    foreach ($inventarios as $claveInventario => $inventarioFecha) {
        $totalLitrosFecha = 0;
        $totalKilosFecha = 0;

        foreach ($inventarioFecha as $claveRuta => $ruta) {
            $productoId = $ruta["producto_id"];
            $inventarioActual = number_format($ruta["inventario_actual"], 2);

            $totalEntradas = $ruta["total_entradas"];
            $totalSalidas = $ruta["total_salidas"];

            if (number_format($totalSalidas, 2) == 0) {
                $inventarioActual = number_format($totalEntradas, 2);
            } else {
                $inventarioActual = number_format(($totalEntradas - $totalSalidas), 2);
            }

            // Si el producto es Lts, realizar cálculo de inventario en base a inventario actual y capacidad de Pipa
            if ($productoId == 4) {
                $litros = (($inventarioActual * $ruta["ruta_capacidad"]) / 100);
                $kilos = ($litros * .524);
            } elseif ($productoId == 6 || $productoId == 7 || $productoId == 8) {
                // Tanques Gasolina
                $litros = $inventarioActual;
                $kilos = ($litros * .524);
            } else {
                // Se calcula los litros vendidos por el cilindro
                $kilos = ($inventarioActual * $ruta["producto_capacidad"]);
                $litros = ($kilos / .524);
            }

            $totalLitrosFecha += $litros;
            $totalKilosFecha += $kilos;
        }

        $totalKgZona += $totalKilosFecha;
        $totalLtsZona += $totalLitrosFecha;
    }

    $data["totalKgZona"] = $totalKgZona; // Cambiado a totalKgZona
    $data["totalLtsZona"] = $totalLtsZona; // Cambiado a totalLtsZona

    return $data;
}

function obtenerTotalComprasTraspasosGasKgZonaFecha($zonaId, $fechaInicial, $fechaFinal)
{
    /* Compras en el caso de plantas
    Sucursales se consideran traspasos recibidos de las plantas como compras */
    $modelCompra = new ModelCompra();
    $modelTraspaso = new ModelTraspaso();
    $modelZona = new ModelZona();
    $total = 0;

    $zona = $modelZona->obtenerZonaId($zonaId);

    if ($zona["tipo_zona_planta_id"] == 2) { // Planta
        $totalData = $modelCompra->obtenerTotalComprasGasZonaFechas($zonaId, $fechaInicial, $fechaFinal);
        $total = $totalData[0]["total"];
    } else if ($zona["tipo_zona_planta_id"] == 3) { // Sucursal *.524
        $totalData = $modelTraspaso->obtenerTotalRecibidosZonaIdEntreFechas($zonaId, $fechaInicial, $fechaFinal);
        $total = $totalData[0]["total"] * 0.524;
		
		if ($zona["idzona"] == 19) {
			$totalDataExtra = $modelTraspaso->obtenerTotalRecibidosZonaIdEntreFechas2($zonaId, $fechaInicial, $fechaFinal);
		
			// Depuración: Ver todos los datos obtenidos
		
			$sumaMenores150 = 0;
			$sumaMayores150 = 0;
		
			if (!empty($totalDataExtra)) {
				foreach ($totalDataExtra as $traspaso) {
					$cantidad = isset($traspaso["cantidad"]) ? (float)$traspaso["cantidad"] : 0;
		
					if ($cantidad < 150) {
						$sumaMenores150 += $cantidad;
					} else {
						$sumaMayores150 += $cantidad;
					}
				}
		
				// Multiplicamos la suma de los menores a 150 por 30
				$totalExtra = $sumaMenores150 * 30;
		
				// Multiplicamos la suma de los mayores o iguales a 150 por 0.524
				$sumaMayores150Kg = $sumaMayores150 * 0.524;
		
				// Sumamos el total extra al total de cantidades mayores a 150 convertidas a Kg
				$totalFinal = $totalExtra + $sumaMayores150Kg;
				
				
			} else {
				echo "No hay datos de traspasos para la zona y el rango de fechas especificados.";
			}
		}
		
		
    }

    $data["totalKgCompras"] = $total;
    return $data;
}
function obtenerTotalTraspasosGasKgZonaFechaEstaciones($zonaId, $fechaInicial, $fechaFinal)
{
    $modelTraspaso = new ModelTraspaso();
    $modelZona = new ModelZona();
    $total = 0;

    $zona = $modelZona->obtenerZonaId($zonaId);

    if ($zona["tipo_zona_planta_id"] == 3) { // Sucursal
        $totalData = $modelTraspaso->obtenerTotalRecibidosZonaIdEntreFechas($zonaId, $fechaInicial, $fechaFinal);
        $total = $totalData[0]["total"] * 0.524;
    }

    $data["totalKgCompras"] = $total;
    return $data;
}




// Función para obtener las estaciones de acuerdo a la zona
function obtenerEstacionesPorCompania($companiaId)
{
    $resultado = $this->base_datos->query("SELECT * FROM rutas 
        WHERE tipo_ruta_id = 5 
        AND zona_id IN (SELECT idzona FROM zonas WHERE compania_id = '$companiaId')");

    // Verificar si la consulta falló
    if (!$resultado) {
        return []; // Retorna un array vacío si la consulta falla
    }

    return $resultado->fetchAll(PDO::FETCH_ASSOC);
}


	function obtenerCompaniasInventarioTeorico()
	{
		$sql = $this->base_datos->query("SELECT c.* 
				FROM companias AS c
				INNER JOIN zonas_inventario AS zi ON c.idcompania = zi.compania_id  
				GROUP BY zi.compania_id
				ORDER BY c.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
		function obtenerEstacionesInventarioTeorico()
	{
		$sql = $this->base_datos->query("select * from rutas where rutas.tipo_ruta_id = 5")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
	public function obtenerEstacionesPorZonaRuta($companiaId, $zonaId, $rutaId) {
        // Consulta SQL para obtener las estaciones asociadas a una ruta de una zona de una compañía
        $sql = "SELECT r.id AS ruta_id, r.nombre AS ruta_nombre, r.estacion AS estacion_nombre
                FROM rutas r
                INNER JOIN zonas z ON r.zona_id = z.id
                WHERE z.compania_id = $companiaId
                AND z.id = $zonaId
                AND r.id = $rutaId
                AND r.estacion IS NOT NULL"; // Filtra solo rutas que tienen estaciones asociadas

        // Ejecutar la consulta directamente en la base de datos
        $result = $this->base_datos->query($sql);

        // Verificar si la consulta fue exitosa
        if ($result) {
            // Obtener y devolver los resultados
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Si la consulta falla, devolver un array vacío
            return [];
        }
    }

	function obtenerZonasInventarioTeorico($companiaId)
	{
		$sql = $this->base_datos->query("SELECT z.*, UPPER(z.nombre) AS nombre 
				FROM zonas AS z
				INNER JOIN zonas_inventario AS zi ON z.idzona = zi.zona_id
				AND zi.compania_id = $companiaId
				ORDER BY zi.ordering  ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}


}
