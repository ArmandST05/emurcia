<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

//TIPO PRESUPUESTO 1 = CONTABLE
//TIPO PRESUPUESTO 1 = CONCEPTOS (NO ESPECIFICAN CANTIDAD MONETARIA)
class ModelPresupuestoConcepto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function insertarPorConcepto($zonaId, $anio, $mes, $conceptoId,$rutaId=null)
	{
		$sql = $this->base_datos->insert("presupuestos", [
			"zona_id" => $zonaId,
			"ruta_id" => $rutaId,
			"anio" => $anio,
			"mes" => $mes,
			"concepto_gasto_id" => $conceptoId,
			"tipo_presupuesto_id" => 2
		]);
		return $this->base_datos->id();
	}

	function eliminarZonaAnioMes($tipoGastoId,$zonaId, $anio, $mes)
	{
		$sql = $this->base_datos->query("DELETE FROM presupuestos 
			WHERE zona_id = '$zonaId' 
			AND anio = '$anio' 
			AND mes = '$mes'
			AND tipo_presupuesto_id = 2
			AND (SELECT categorias_gasto.tipo_gasto_id 
				FROM categorias_gasto,conceptos_gasto 
				WHERE categorias_gasto.idcategoriagasto = conceptos_gasto.categoria_gasto_id 
				AND presupuestos.concepto_gasto_id = conceptos_gasto.idconceptogasto) = '$tipoGastoId'
		")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function eliminarZonaRutaAnioMes($tipoGastoId,$zonaId,$rutaId,$anio,$mes)
	{
		$sql = $this->base_datos->query("DELETE FROM presupuestos 
			WHERE zona_id = '$zonaId' 
			AND ruta_id = '$rutaId' 
			AND anio = '$anio' 
			AND mes = '$mes'
			AND tipo_presupuesto_id = 2
			AND (SELECT categorias_gasto.tipo_gasto_id 
				FROM categorias_gasto,conceptos_gasto 
				WHERE categorias_gasto.idcategoriagasto = conceptos_gasto.categoria_gasto_id 
				AND presupuestos.concepto_gasto_id = conceptos_gasto.idconceptogasto) = '$tipoGastoId'
		")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerZonaMesAnio($tipoGastoId,$zonaId,$mes,$anio)
	{
		//Obtener de una zona en cierta fecha todos los conceptos seleccionados como posibles gastos (presupuesto concepto)
		$sql = $this->base_datos->query("SELECT conceptos_gasto.idconceptogasto AS concepto_id,conceptos_gasto.nombre AS concepto_nombre,
			presupuestos.cantidad AS presupuesto_cantidad,
			(SELECT SUM(gastos.cantidad) 
				FROM gastos
				WHERE gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
				AND gastos.mes = '$mes'
				AND gastos.anio = '$anio'
				AND gastos.zona_id = '$zonaId'
				AND gastos.tipo_gasto_id = '$tipoGastoId') AS total_gastos
			FROM conceptos_gasto
			INNER JOIN presupuestos ON conceptos_gasto.idconceptogasto = presupuestos.concepto_gasto_id
				AND presupuestos.mes = '$mes'
				AND presupuestos.anio = '$anio'
				AND presupuestos.zona_id = '$zonaId'
				AND presupuestos.tipo_presupuesto_id = 2
			INNER JOIN categorias_gasto ON conceptos_gasto.categoria_gasto_id = categorias_gasto.idcategoriagasto
				AND categorias_gasto.tipo_gasto_id = '$tipoGastoId'
			ORDER BY conceptos_gasto.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerZonaRutaMesAnio($zonaId,$rutaId,$mes,$anio)
	{
		//Obtener de una ruta en cierta fecha todos los conceptos seleccionados como posibles gastos (presupuesto conceptos)
		//Tipo Gasto = 2 es gasto por punto venta/ruta
		//Tipo presupuesto = 2 es presupuesto por conceptos
		$sql = $this->base_datos->query("SELECT conceptos_gasto.idconceptogasto AS concepto_id,conceptos_gasto.nombre AS concepto_nombre,
			presupuestos.cantidad AS presupuesto_cantidad,
			(SELECT SUM(gastos.cantidad) 
				FROM gastos
				WHERE gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto 
				AND gastos.mes = '$mes'
				AND gastos.anio = '$anio'
				AND gastos.zona_id = '$zonaId' 
				AND gastos.ruta_id = '$rutaId' 
				AND gastos.tipo_gasto_id = 2) AS total_gastos
			FROM conceptos_gasto
			INNER JOIN presupuestos ON conceptos_gasto.idconceptogasto = presupuestos.concepto_gasto_id
				AND presupuestos.mes = '$mes'
				AND presupuestos.anio = '$anio'
				AND presupuestos.zona_id = '$zonaId'
				AND presupuestos.ruta_id = '$rutaId'
				AND presupuestos.tipo_presupuesto_id = 2
			INNER JOIN categorias_gasto ON conceptos_gasto.categoria_gasto_id = categorias_gasto.idcategoriagasto
				AND categorias_gasto.tipo_gasto_id = 2
			ORDER BY conceptos_gasto.nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
}
