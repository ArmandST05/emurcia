<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->baseDatos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->baseDatos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->baseDatos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->baseDatos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelZona
{
	var $baseDatos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD
	var $tabla = "zonas";
	var $tablaLocalidades = "zonas_localidades";

	function __construct(){ 
		//Constructor de la conexion a la BD
		$this->baseDatos = new Medoo();
	}

	function buscarZonaNombre($zona){
		$sql = $this->baseDatos->query("SELECT * FROM $this->tabla 
		WHERE  nombre = '$zona' LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerZonaId($id){
		$sql = $this->baseDatos->get("$this->tabla", "*", ["idzona[=]" => $id]);
		return $sql;
	}

	function obtenerZonasTodas(){
		$resultado = $this->baseDatos->query("SELECT idzona,UPPER(nombre) AS nombre,
		tipo_zona_id,compania_id,estatus,valor_punto FROM $this->tabla ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerZonasPorUsuario($usuarioId){
		$resultado = $this->baseDatos->query("SELECT idzona,UPPER(nombre) AS nombre,tipo_zona_id,compania_id,estatus 
			FROM $this->tabla,usuario_zonas uz
			WHERE $this->tabla.idzona = uz.zona_id
			AND uz.usuario_id = $usuarioId
			ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerTodas(){
		$sql = $this->baseDatos->query("SELECT idzona,nombre,tipo_zona_id,compania_id,estatus 
		FROM $this->tabla ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;  
	}

	function listaTodas(){
		$resultado = $this->baseDatos->query("SELECT idzona,UPPER(nombre) AS nombre,
		tipo_zona_id,compania_id,estatus FROM $this->tabla ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerZonaUsuario($zona){
		$resultado = $this->baseDatos->query("SELECT * FROM $this->tabla WHERE nombre='$zona'")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerPorCompaniaEstatus($companiaId,$estatusId){
		$sql = "SELECT idzona,UPPER(nombre) AS nombre,tipo_zona_id,compania_id,estatus 
		FROM $this->tabla WHERE estatus = '$estatusId' ";
		if($companiaId != "0"){
			$sql.= " AND compania_id='$companiaId' ";
		}
		$resultado = $this->baseDatos->query($sql)->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerZonasGas(){
		$resultado = $this->baseDatos->query("SELECT idzona,UPPER(nombre) AS nombre,
			tipo_zona_id,compania_id,estatus,valor_punto 
			FROM $this->tabla WHERE tipo_zona_id = 1 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerZonasPrecioGas(){
		//Obtener las zonas de gas (1) y las zonas de tipo precio gas (3) para mostrar en la sección donde se registran los precios.
		$resultado = $this->baseDatos->query("SELECT idzona,UPPER(nombre) AS nombre,
			tipo_zona_id,compania_id,estatus,valor_punto
			FROM $this->tabla WHERE tipo_zona_id = 1 OR tipo_zona_id = 3 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function obtenerZonasGasolina(){
		$resultado = $this->baseDatos->query("SELECT idzona,nombre,tipo_zona_id,compania_id,estatus,valor_punto 
			FROM $this->tabla WHERE tipo_zona_id = 2 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $resultado;  
	}

	function actualizarValorPunto($zonaId,$valorPunto)
	{
		$this->baseDatos->update($this->tabla, [
			"valor_punto" => $valorPunto
		], ["idzona[=]" => $zonaId]);
	}

	/*LOCALIDADES POR ZONA */
	function insertarZonaLocalidad($zonaId,$localidadId)
	{
		$this->baseDatos->insert($this->tablaLocalidades, [
			"zona_id" => $zonaId,
			"localidad_id" => $localidadId
		]);
	}

	function eliminarLocalidadesZonaMunicipio($zonaId,$municipioId)
	{
		$sql = $this->baseDatos->query("DELETE FROM $this->tablaLocalidades 
		WHERE zona_id = '$zonaId'
		AND (SELECT municipio_id FROM localidades WHERE localidades.id = $this->tablaLocalidades.localidad_id) = '$municipioId' ")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}


	function obtenerZonaPorId($idZona) {
		// Obtener la zona basada en el ID proporcionado usando Medoo
		return $this->baseDatos->get($this->tabla, "*", [
			"idzona" => $idZona
		]);
	}
	
		
}
