<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCiudad
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct(){ 
		//Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaPorZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM ciudades WHERE idzona='$zonaId' AND status='0' ORDER by ciudad")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPorNombre($nombre){
		$sql = $this->base_datos->get("ciudades","*",
			["AND" => [
				"ciudad[=]" => $nombre
			]
		]);
	return $sql;
	}

}