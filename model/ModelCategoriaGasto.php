<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCategoriaGasto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{
		//Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaPorTipoGasto($tipoGasto)
	{
		$sql = $this->base_datos->select("categorias_gasto", "*", ["tipo_gasto_id" => $tipoGasto]);
		return $sql;
	}

	function insertar($tipoGasto, $nombre)
	{
		$sql = $this->base_datos->insert(
			"categorias_gasto",
			[
				"tipo_gasto_id" => $tipoGasto,
				"nombre" => $nombre
			]
		);
		return $this->base_datos->id();
	}

	function actualizar($id, $nombre)
	{
		$sql = $this->base_datos->update("categorias_gasto", ["nombre" => $nombre,], ["idcategoriagasto[=]" => $id]);
		return $sql;
	}

	function activar($id)
	{
		$sql = $this->base_datos->update("categorias_gasto", [
			"estatus" => 1
		], ["idcategoriagasto[=]" => $id]);
		return $sql;
	}

	function desactivar($id)
	{
		$sql = $this->base_datos->update("categorias_gasto", [
			"estatus" => 0
		], ["idcategoriagasto[=]" => $id]);
		return $sql;
	}
}
