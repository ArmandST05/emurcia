<?php
include_once('Medoo.php');

use Medoo\Medoo;

/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCompra
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function validarCompra($zonaId, $no)
	{
		$resultado = $this->base_datos->query("select count(nocompra) as numero 
		from compras_gas 
		where zona_id='$zonaId' and nocompra='$no'")->fetchAll();
		return $resultado;
	}

	function insertarCompraGas($nocompra, $nombrep, $destino, $kilos, $origen, $fecha, $zonaId, $fechae, $fechapago, $densidad, $litros, $descarga)
	{
		$this->base_datos->insert("compras_gas", [
			"nocompra" => $nocompra,
			"proveedor" => $nombrep,
			"destino" => $destino,
			"kilogramos" => $kilos,
			"origen" => $origen,
			"zona_id" => $zonaId,
			"fechacompra" => $fecha,
			"fechaembarque" => $fechae,
			"fechapago" => $fechapago,
			"densidad" => $densidad,
			"litros" => $litros,
			"descargada" => $descarga
		]);
		return $this->base_datos->id();
	}

	function obtenerZonaFechas($zonaId, $fechai, $fechaf)
	{
		$resultado = $this->base_datos->query("SELECT cg.*,z.nombre as zona_nombre 
		FROM compras_gas cg
		INNER JOIN zonas z ON z.idzona = cg.zona_id
		WHERE cg.zona_id = '$zonaId' 
		AND cg.fechacompra BETWEEN '$fechai' AND '$fechaf' ORDER BY fechacompra ASC");
		return $resultado;
	}

	function actualizar($id, $nocompra, $proveedor, $origen, $kilogramos, $destino, $fecha, $fechae, $precio, $venta, $fechap, $descargada)
	{
		$this->base_datos->update("compras_gas", [
			"fechacompra" => $fecha,
			"nocompra" => $nocompra,
			"proveedor" => $proveedor,
			"origen" => $origen,
			"kilogramos" => $kilogramos,
			"destino" => $destino,
			"fechaembarque" => $fechae,
			"preciokg" => $precio,
			"venta_total" => $venta,
			"fechapago" => $fechap,
			"descargada" => $descargada
		], ["idcompragas[=]" => $id]);
	}

	function eliminarGas($id)
	{
		$this->base_datos->delete("compras_gas", ["idcompragas[=]" => $id]);
	}

	function obtenerTotalComprasGasZonaFechas($zonaId,$fechaInicial,$fechaFinal)
	{
		//Obtiene las compras de acuerdo a su fecha de descarga
		$resultado = $this->base_datos->query("SELECT SUM(kilogramos) AS total
			FROM compras_gas cg
			INNER JOIN zonas z ON z.idzona = cg.zona_id
			WHERE cg.zona_id = '$zonaId' 
			AND cg.fechaembarque BETWEEN '$fechaInicial' AND '$fechaFinal' ")->fetchAll(PDO::FETCH_ASSOC);
			return $resultado;
	}
}
