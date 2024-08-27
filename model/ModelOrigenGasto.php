<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelOrigenGasto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{
		//Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function index()
	{
		$sql = $this->base_datos->select(
			"origenes_gasto",
			"*",
			["ORDER" => "nombre"]
		);
		return $sql;
	}

	function listaPorEstatus($estatus)
	{
		$sql = $this->base_datos->select(
			"origenes_gasto",
			"*",
			["estatus" => $estatus],
			["ORDER" => "nombre"]
		);
		return $sql;
	}

	//Utilizado al editar un gasto, obtiene los orígenes activos 
	//y el origen con el que se guardó el gasto para mostrarlo aunque se desactivara
	function listaPorEstatusGastoEditar($origenId, $estatus)
	{
		$sql = $this->base_datos->select(
			"origenes_gasto",
			"*",
			["OR" => ["estatus[=]" => $estatus, "idorigengasto[=]" => $origenId]],
			["ORDER" => "nombre"]
		);
		return $sql;
	}

	function insertar($nombre)
	{
		$sql = $this->base_datos->insert(
			"origenes_gasto",
			[
				"nombre" => $nombre,
				"estatus" => 1
			]
		);
		return $this->base_datos->id();
	}

	function actualizar($id, $nombre)
	{
		$sql = $this->base_datos->update(
			"origenes_gasto",
			[
				"nombre" => $nombre
			],
			["idorigengasto[=]" => $id]
		);
		return $sql;
	}

	function activar($id)
	{
		$this->base_datos->update("origenes_gasto", [
			"estatus" => 1
		], ["idorigengasto[=]" => $id]);
	}

	function desactivar($id)
	{
		$this->base_datos->update("origenes_gasto", [
			"estatus" => 0
		], ["idorigengasto[=]" => $id]);
	}
}
