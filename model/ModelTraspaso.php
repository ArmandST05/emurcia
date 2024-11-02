<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelTraspaso
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerEnviadosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin)
{
    $resultado = $this->base_datos->query("SELECT 
            traspasos.idtraspaso,
            traspasos.fecha,
            zona_origen.nombre AS zona_origen,
            zona_destino.nombre AS zona_destino,
            traspasos.cantidad,
            traspasos.estatus_traspaso_id AS estatus_id,
            estatus_traspaso.nombre AS estatus_nombre,
            traspasos.comprobante_traspaso  -- Agregando el campo comprobante_traspaso
        FROM 
            traspasos,
            zonas AS zona_origen,
            zonas AS zona_destino,
            estatus_traspaso
        WHERE 
            zona_origen.idzona = traspasos.zona_origen_id 
            AND zona_destino.idzona = traspasos.zona_destino_id 
            AND estatus_traspaso.idestatustraspaso = traspasos.estatus_traspaso_id
            AND zona_origen_id = '$zonaId' 
            AND fecha >= '$fechaInicio'
            AND fecha <= '$fechaFin'")->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}

function obtenerRecibidosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin)
{
    $resultado = $this->base_datos->query("SELECT 
            traspasos.idtraspaso,
            traspasos.fecha,
            zona_origen.nombre AS zona_origen,
            zona_destino.nombre AS zona_destino,
            traspasos.cantidad,
            traspasos.estatus_traspaso_id AS estatus_id,
            estatus_traspaso.nombre AS estatus_nombre,
            traspasos.comprobante_traspaso  -- Agregado el campo comprobante_traspaso
        FROM 
            traspasos,
            zonas AS zona_origen,
            zonas AS zona_destino,
            estatus_traspaso
        WHERE 
            zona_origen.idzona = traspasos.zona_origen_id 
            AND zona_destino.idzona = traspasos.zona_destino_id 
            AND estatus_traspaso.idestatustraspaso = traspasos.estatus_traspaso_id
            AND zona_destino_id ='" . $zonaId . "' 
            AND fecha >= '$fechaInicio'
            AND fecha <= '$fechaFin'
            ")->fetchAll(PDO::FETCH_ASSOC);
    return $resultado;
}


function obtenerTotalRecibidosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin)
{
    // Solo aceptados
    $resultado = $this->base_datos->query("SELECT SUM(cantidad) AS total
        FROM 
            traspasos,
            zonas AS zona_origen, 
            zonas AS zona_destino, 
            estatus_traspaso
        WHERE 
            zona_origen.idzona = traspasos.zona_origen_id 
            AND zona_destino.idzona = traspasos.zona_destino_id 
            AND estatus_traspaso.idestatustraspaso = traspasos.estatus_traspaso_id
            AND zona_destino_id ='" . $zonaId . "' 
            AND fecha >= '$fechaInicio'
            AND fecha <= '$fechaFin'
            AND traspasos.estatus_traspaso_id = 1
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    return $resultado;
}
function obtenerTotalRecibidosEstacionesEntreFechas($zonaId, $fechaInicio, $fechaFin)
{
    // Solo aceptados
    $resultado = $this->base_datos->query("SELECT SUM(cantidad) AS total
        FROM 
            traspasos,
            zonas AS zona_origen, 
            zonas AS zona_destino, 
            estatus_traspaso
        WHERE 
            zona_origen.idzona = traspasos.zona_origen_id 
            AND zona_destino.idzona = traspasos.zona_destino_id 
            AND estatus_traspaso.idestatustraspaso = traspasos.estatus_traspaso_id
            AND zona_destino_id = '" . $zonaId . "' 
            AND fecha >= '$fechaInicio'
            AND fecha <= '$fechaFin'
            AND traspasos.estatus_traspaso_id = 1
            AND zona_destino.clave_ruta LIKE '%est.%' 
            OR zona_destino.clave_ruta LIKE '%Est%' 
            OR zona_destino.clave_ruta LIKE '%Estacion%' 
            OR zona_destino.clave_ruta LIKE '%Estación%'
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    return $resultado;
}



function obtenerTotalEnviadosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin)
{
    $resultado = $this->base_datos->query("SELECT SUM(cantidad) AS total
        FROM 
            traspasos,
            zonas AS zona_origen, 
            zonas AS zona_destino, 
            estatus_traspaso
        WHERE 
            zona_origen.idzona = traspasos.zona_origen_id 
            AND zona_destino.idzona = traspasos.zona_destino_id 
            AND estatus_traspaso.idestatustraspaso = traspasos.estatus_traspaso_id
            AND zona_origen_id ='" . $zonaId . "' 
            AND fecha >= '$fechaInicio'
            AND fecha <= '$fechaFin'
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    return $resultado;
}


	function obtenerId($id)
	{
		$sql = $this->base_datos->query("SELECT * FROM traspasos WHERE idtraspaso='" . $id . "'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertar($fecha, $zonaOrigen, $zonaDestino, $cantidad, $comprobanteTraspaso)
	{
		$this->base_datos->insert("traspasos", [
			"fecha" => $fecha,
			"zona_origen_id" => $zonaOrigen,
			"zona_destino_id" => $zonaDestino,
			"cantidad" => $cantidad,
			"estatus_traspaso_id" => 2,
			"comprobante_traspaso" => $comprobanteTraspaso
		]);
		return $this->base_datos->id();
	}

	function aceptar($id)
	{
		$this->base_datos->update("traspasos", [
			"estatus_traspaso_id" => 1,
		], ["idtraspaso[=]" => $id]);
	}

	function rechazar($id)
	{
		$this->base_datos->update("traspasos", [
			"estatus_traspaso_id" => 3,
		], ["idtraspaso[=]" => $id]);
	}

	function eliminar($id)
	{
		$this->base_datos->delete("traspasos", ["idtraspaso[=]" => $id]);
	}
}
