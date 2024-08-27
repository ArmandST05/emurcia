<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelConceptoGasto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{
		//Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaPorTipoGastoEstatus($tipoGasto, $estatus)
	{
		$sql = $this->base_datos->query("SELECT conceptos_gasto.idconceptogasto,conceptos_gasto.nombre 
			FROM conceptos_gasto, categorias_gasto
			WHERE conceptos_gasto.categoria_gasto_id = categorias_gasto.idcategoriagasto
			AND categorias_gasto.tipo_gasto_id = '$tipoGasto'
			AND categorias_gasto.estatus = '$estatus'
			AND conceptos_gasto.estatus = '$estatus'
			ORDER BY conceptos_gasto.nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	//Utilizado al editar un gasto, obtiene los conceptos activos 
	//y el concepto con el que se guardó el gasto para mostrarlo aunque se desactivara
	function listaPorTipoGastoEstatusGastoEditar($conceptoId, $tipoGasto, $estatus)
	{
		$sql = $this->base_datos->query("SELECT conceptos_gasto.idconceptogasto,conceptos_gasto.nombre 
			FROM conceptos_gasto, categorias_gasto
			WHERE conceptos_gasto.categoria_gasto_id = categorias_gasto.idcategoriagasto
			AND categorias_gasto.tipo_gasto_id = '$tipoGasto'
			AND ((categorias_gasto.estatus = '$estatus' AND conceptos_gasto.estatus = '$estatus') 
			OR conceptos_gasto.idconceptogasto = '$conceptoId')
			ORDER BY conceptos_gasto.nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaPorCategoria($categoriaId)
	{
		$sql = $this->base_datos->select("conceptos_gasto", "*", ["categoria_gasto_id" => $categoriaId]);
		return $sql;
	}

	function insertar($categoriaId, $nombre)
	{
		$sql = $this->base_datos->insert(
			"conceptos_gasto",
			[
				"categoria_gasto_id" => $categoriaId,
				"nombre" => $nombre
			]
		);
		return $this->base_datos->id();
	}

	function actualizar($id, $categoriaId, $nombre)
	{
		$sql = $this->base_datos->update(
			"conceptos_gasto",
			[
				"categoria_gasto_id" => $categoriaId,
				"nombre" => $nombre
			],
			["idconceptogasto[=]" => $id]
		);
		return $sql;
	}

	function activar($id)
	{
		$this->base_datos->update("conceptos_gasto", [
			"estatus" => 1
		], ["idconceptogasto[=]" => $id]);
	}

	function activarPorCategoria($categoriaId)
	{
		$this->base_datos->update("conceptos_gasto", [
			"estatus" => 1
		], ["categoria_gasto_id[=]" => $categoriaId]);
	}

	function desactivar($id)
	{
		$this->base_datos->update("conceptos_gasto", [
			"estatus" => 0
		], ["idconceptogasto[=]" => $id]);
	}

	function desactivarPorCategoria($categoriaId)
	{
		$this->base_datos->update("conceptos_gasto", [
			"estatus" => 0
		], ["categoria_gasto_id[=]" => $categoriaId]);
	}
}
