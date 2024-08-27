<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->baseDatos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->baseDatos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->baseDatos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->baseDatos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelMunicipio
{
	var $baseDatos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD
	var $tabla;

	function __construct(){ 
		//Constructor de la conexion a la BD
		$this->baseDatos = new Medoo();
		$this->tabla = "municipios";
	}

	function obtenerPorId($id)
	{
		$sql = $this->baseDatos->query("SELECT * FROM $this->tabla
			WHERE idmunicipio = '$id'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function buscarPorEstado($estadoId,$busqueda)
	{
		//Utilizada en APP TARJETA PUNTOS
		$sql = "SELECT * FROM $this->tabla 
		WHERE estado_id='$estadoId' ";

		if($busqueda){
			$sql.="AND nombre LIKE '".$busqueda."%' ";
		}
		$sql.=" ORDER BY nombre ASC";

		$sql = $this->baseDatos->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

}
