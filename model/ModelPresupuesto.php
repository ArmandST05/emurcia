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
class ModelPresupuesto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function insertarPorCategoria($zonaId, $anio, $mes, $categoriaId, $cantidad)
	{
		$sql = $this->base_datos->get(
			"presupuestos",
			"*",
			["AND" => [
				"zona_id[=]" => $zonaId,
				"anio[=]" => $anio,
				"mes[=]" => $mes,
				"categoria_gasto_id[=]" => $categoriaId,
				"tipo_presupuesto_id[=]" => 1
			]]
		);
		if ($sql) {
			//Si existe actualizar
			$sql = $this->base_datos->update("presupuestos", [
				"cantidad" => $cantidad,
			], ["idpresupuesto[=]" => $sql['idpresupuesto']]);
		} else {
			//Si no existe crear
			$sql = $this->base_datos->insert("presupuestos", [
				"zona_id" => $zonaId,
				"anio" => $anio,
				"mes" => $mes,
				"categoria_gasto_id" => $categoriaId,
				"cantidad" => $cantidad,
				"tipo_presupuesto_id" => 1
			]);
		}
		return $this->base_datos->id();
	}

	function insertarPorConcepto($zonaId, $anio, $mes, $conceptoId, $cantidad)
	{
		$sql = $this->base_datos->get(
			"presupuestos",
			"*",
			["AND" => [
				"zona_id[=]" => $zonaId,
				"anio[=]" => $anio,
				"mes[=]" => $mes,
				"concepto_gasto_id[=]" => $conceptoId,
				"tipo_presupuesto_id[=]" => 1
			]]
		);
		if ($sql) {
			//Si existe actualizar
			$sql = $this->base_datos->update("presupuestos", [
				"cantidad" => $cantidad,
			], ["idpresupuesto[=]" => $sql['idpresupuesto']]);
		} else {
			//Si no existe crear
			$sql = $this->base_datos->insert("presupuestos", [
				"zona_id" => $zonaId,
				"anio" => $anio,
				"mes" => $mes,
				"concepto_gasto_id" => $conceptoId,
				"cantidad" => $cantidad,
				"tipo_presupuesto_id" => 1
			]);
		}
		return $this->base_datos->id();
	}

	function listaPresupuestoCategoriasPorTipoGastoZonaFechas($tipoGasto, $zonaId, $fechaInicial, $fechaFinal)
	{
		//La consulta es por meses/año pero enviamos fecha completa
		$sql = $this->base_datos->query("SELECT calendario.calendario_mes AS mes,
			calendario.calendario_anio AS anio,
			calendario.calendario_anio_mes AS anio_mes,
			categorias_gasto.id,
			categorias_gasto.nombre,
			categorias_gasto.estatus,
			presupuestos.cantidad AS presupuesto_categoria,
			(SELECT SUM(presupuestos.cantidad) 
				FROM presupuestos,conceptos_gasto,categorias_gasto
				WHERE presupuestos.concepto_gasto_id = conceptos_gasto.idconceptogasto 
				AND categorias_gasto.idcategoriagasto = conceptos_gasto.categoria_gasto_id
				AND categorias_gasto.estatus = 1
				AND categorias_gasto.tipo_gasto_id = '$tipoGasto'
				AND presupuestos.mes = calendario.calendario_mes
				AND presupuestos.anio = calendario.calendario_anio
				AND presupuestos.zona_id = '$zonaId'
				AND presupuestos.tipo_presupuesto_id = 1) AS presupuesto_conceptos,
			(SELECT SUM(gastos.cantidad) 
				FROM gastos,conceptos_gasto,categorias_gasto
				WHERE gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto 
				AND categorias_gasto.idcategoriagasto = conceptos_gasto.categoria_gasto_id
				AND categorias_gasto.estatus = 1
				AND categorias_gasto.tipo_gasto_id = '$tipoGasto'
				AND gastos.mes = calendario.calendario_mes
				AND gastos.anio = calendario.calendario_anio
				AND gastos.zona_id = '$zonaId') AS total_gastos
			FROM categorias_gasto
			CROSS JOIN (
				SELECT DATE_FORMAT(calendario_fecha,'%m') AS calendario_mes,
				DATE_FORMAT(calendario_fecha,'%Y') AS calendario_anio,
				DATE_FORMAT(calendario_fecha,'%m-%Y') AS calendario_anio_mes
				FROM (
				SELECT adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) calendario_fecha 
				FROM            
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4
				) v WHERE calendario_fecha BETWEEN '$fechaInicial' AND '$fechaFinal' 
				GROUP BY DATE_FORMAT(calendario_fecha,'%m-%Y')
			) calendario
			LEFT JOIN presupuestos ON categorias_gasto.id = presupuestos.categoria_gasto_id
				AND presupuestos.mes = calendario.calendario_mes
				AND presupuestos.anio = calendario.calendario_anio
				AND presupuestos.zona_id = '$zonaId'
				AND presupuestos.tipo_presupuesto_id = 1
			WHERE categorias_gasto.tipo_gasto_id = '$tipoGasto'
			AND categorias_gasto.estatus = 1
			ORDER BY calendario.calendario_mes,calendario.calendario_anio ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaPresupuestoConceptosPorCategoriaZonaFechas($categoriaId, $zonaId, $fechaInicial, $fechaFinal)
	{
		//La consulta es por meses/año pero enviamos fecha completa
		$sql = $this->base_datos->query("SELECT calendario.calendario_mes AS mes,
			calendario.calendario_anio AS anio,
			calendario.calendario_anio_mes AS anio_mes,
			conceptos_gasto.idconceptogasto,
			conceptos_gasto.nombre,
			conceptos_gasto.estatus,
			presupuestos.cantidad AS presupuesto_concepto,
			(SELECT SUM(gastos.cantidad) 
				FROM gastos
				WHERE gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
				AND gastos.mes = calendario.calendario_mes
				AND gastos.anio = calendario.calendario_anio
				AND gastos.zona_id = '$zonaId') AS total_gastos
			FROM conceptos_gasto
			CROSS JOIN (
				SELECT DATE_FORMAT(calendario_fecha,'%m') AS calendario_mes,
				DATE_FORMAT(calendario_fecha,'%Y') AS calendario_anio,
				DATE_FORMAT(calendario_fecha,'%m-%Y') AS calendario_anio_mes
				FROM (
				SELECT adddate('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) calendario_fecha 
				FROM            
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t0,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t1,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t2,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t3,
				(SELECT 0 i union select 1 union select 2 union select 3 union select 4 union select 5 union select 6 union select 7 union select 8 union select 9) t4
				) v WHERE calendario_fecha BETWEEN '$fechaInicial' AND '$fechaFinal' 
				GROUP BY DATE_FORMAT(calendario_fecha,'%m-%Y')
			) calendario
			LEFT JOIN presupuestos ON conceptos_gasto.idconceptogasto = presupuestos.concepto_gasto_id
				AND presupuestos.mes = calendario.calendario_mes
				AND presupuestos.anio = calendario.calendario_anio
				AND presupuestos.zona_id = '$zonaId'
				AND presupuestos.tipo_presupuesto_id = 1
			WHERE conceptos_gasto.categoria_gasto_id = '$categoriaId'
			AND conceptos_gasto.estatus = 1
			ORDER BY calendario.calendario_mes,calendario.calendario_anio ASC")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
}
