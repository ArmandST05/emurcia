<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelPermiso
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerPermisosZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT permisos.nombre
			FROM permisos,permisos_zona
			WHERE permisos.idpermiso = permisos_zona.permiso_id
			AND permisos_zona.zona_id = '$zonaId'
			ORDER BY permisos.nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaPorRuta($rutaId)
	{
		$sql = $this->base_datos->query("SELECT permisos.idpermiso,permisos.nombre
			FROM permisos,permisos_ruta
			WHERE permisos.idpermiso = permisos_ruta.permiso_id
			AND permisos_ruta.ruta_id = '$rutaId'
			ORDER BY permisos.nombre")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function listaTodos()
	{
		$sql = $this->base_datos->select("permisos","*",["ORDER" =>["permisos.nombre"]]);
		return $sql;
	}


	//PERMISOS DE RUTA/UNIDAD
	function insertarPermisoRuta($permisoId,$rutaId)
	{
		$this->base_datos->insert("permisos_ruta", [
			"permiso_id" => $permisoId,
			"ruta_id" => $rutaId
		]);
		return $this->base_datos->id();
	}

	function eliminarPorRuta($rutaId)
	{
		$sql = $this->base_datos->delete("permisos_ruta", ["ruta_id[=]" => $rutaId]);
		return $sql;
	}
}
