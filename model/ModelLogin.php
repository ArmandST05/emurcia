<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->baseDatos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->baseDatos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->baseDatos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->baseDatos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelLogin
{
	var $baseDatos; //Variable para hacer la conexion a la base de datos
	var $resultadoDatos; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->baseDatos = new Medoo();
	}

	function validarLogin($username, $password)
	{
		$resultadoDatos = $this->baseDatos->get("usuarios", "*", ["AND" => ["usuario[=]" => $username, "password[=]" => $password]]);
		return $resultadoDatos;
	}

	function insertarUsuario($usuario, $contrasena, $zonaId, $zonaNombre, $tipoUsuario,$empleadoId,$zonas)
	{
		$this->baseDatos->insert("usuarios", [
			"usuario" => $usuario,
			"password" => $contrasena,
			"zona_id" => $zonaId,
			"zona" => $zonaNombre,
			"tipo_usuario" => $tipoUsuario
		]);
		$usuarioId = $this->baseDatos->id();

		if($usuarioId){
			if($empleadoId){
				$this->baseDatos->update("empleados", [
					"usuario_id" => $usuarioId,
				], ["idempleado[=]" => $empleadoId]);
			}

			if($tipoUsuario == "mp"){
				foreach($zonas as $zona){
					$this->baseDatos->insert("usuario_zonas", [
						"usuario_id" => $usuarioId,
						"zona_id" => $zona
					]);
				}
				
			}

		}
	}

	function obtenerUsuario($usuario)
	{
		$resultadoDatos = $this->baseDatos->get("usuarios", "*", ["usuario[=]" => $usuario]);
		return $resultadoDatos;
	}

	function obtenerUsuarioZonas($usuario)
	{
		$resultadoDatos = $this->baseDatos->select("usuario_zonas", "*", ["AND" => ["usuario_id[=]" => $usuario]]);
		return $resultadoDatos;
	}

	function obtenerUsuarioId($usuarioId)
	{
		$sql = $this->baseDatos->query("SELECT u.*,tu.nombre AS tipo_usuario_nombre,z.nombre as zona_nombre,
		(SELECT idempleado FROM empleados WHERE empleados.usuario_id = u.idusuario) AS empleado_id
		FROM usuarios u
		LEFT JOIN zonas z ON z.idzona = u.zona_id
		INNER JOIN tipos_usuario tu ON u.tipo_usuario = tu.clave
		WHERE u.idusuario = $usuarioId")->fetchAll(PDO::FETCH_ASSOC);;
		if($sql){
			return $sql[0];
		}else return null;
	}

	function listaSuperUsuarios()
	{
		$resultadoDatos = $this->baseDatos->select("usuarios", "*", ["AND" => ["tipo_usuario[=]" => "su"]]);
		return $resultadoDatos;
	}

	function obtenerTiposUsuarioAsignables()
	{
		return $this->baseDatos->query("SELECT * FROM tipos_usuario 
		WHERE clave != 'su' AND clave != 'cli'
		AND clave != 'mv' ");
	}

	function listaUsuarios()
	{
		$sql = $this->baseDatos->query("SELECT u.*,tu.nombre AS tipo_usuario_nombre,z.nombre as zona_nombre,
		(SELECT nombre FROM empleados WHERE empleados.usuario_id = u.idusuario) AS empleado_nombre
		FROM usuarios u
		LEFT JOIN zonas z ON z.idzona = u.zona_id
		INNER JOIN tipos_usuario tu ON u.tipo_usuario = tu.clave
		WHERE u.tipo_usuario != 'su' 
		AND u.tipo_usuario != 'cli'");
		return $sql;
	}

	function actualizar($usuario, $contrasena, $zonaId, $zonaNombre, $tipoUsuario, $id,$empleadoId,$zonas)
	{
		$this->baseDatos->update("usuarios", [
			"usuario" => $usuario,
			"zona_id" => $zonaId,
			"zona" => $zonaNombre,
			"tipo_usuario" => $tipoUsuario
		], ["idusuario[=]" => $id]);

		if($contrasena){
			$this->baseDatos->update("usuarios", [
				"password" => $contrasena,
			], ["idusuario[=]" => $id]);
		}

		$this->baseDatos->update("empleados", [
			"usuario_id" => null,
		], ["usuario_id[=]" => $id]);
		
		if($id && $empleadoId){
			$this->baseDatos->update("empleados", [
				"usuario_id" => $id,
			], ["idempleado[=]" => $empleadoId]);

		}

		$this->baseDatos->delete("usuario_zonas", ["usuario_id[=]" => $id]);
		if($tipoUsuario == "mp"){
			foreach($zonas as $zona){
				$this->baseDatos->insert("usuario_zonas", [
					"usuario_id" => $id,
					"zona_id" => $zona
				]);
			}
			
		}
	}

	function eliminar_usuario($id)
	{
		$this->baseDatos->delete("usuarios", ["idusuario[=]" => $id]);
	}
}
