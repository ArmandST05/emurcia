<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

//DIRECCIÓN 1(ENVIADO), 2 (RECIBIDO O RESPUESTA)
//ESTATUS 1(ENVIADO),2(RECIBIDO),3(NO ENTREGADO)
//MÓDULOS 1(PEDIDOS),2 (PRÓXIMOS PEDIDOS)

class ModelSmsEnviado
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerEnviadosResumenFechas($fechaInicial, $fechaFinal)
	{
		//Convertir formato de fecha inicial y final
		$fechaInicial = $fechaInicial." 00:00:00";
		$fechaFinal = $fechaFinal." 23:59:59";
		//Muestra un TOTAL GENERAL de SMS enviados por ZONAS DE GAS en los módulos de aviso a unidades y próximos pedidos.
		$sql = $this->base_datos->query("SELECT zonas.idzona as zona_id, 
			UPPER(zonas.nombre) as zona_nombre,
			(SELECT COUNT(idsmsenviado) FROM sms_enviados 
			WHERE sms_enviados.zona_id = zonas.idzona 
			AND sms_enviados.modulo_envio_sms_id = 1
			AND sms_enviados.fecha >= '$fechaInicial'
			AND sms_enviados.fecha <= '$fechaFinal') AS total_pedidos,
			(SELECT COUNT(idsmsenviado) FROM sms_enviados 
			WHERE sms_enviados.zona_id = zonas.idzona 
			AND sms_enviados.modulo_envio_sms_id = 2
			AND sms_enviados.fecha >= '$fechaInicial'
			AND sms_enviados.fecha <= '$fechaFinal') AS total_proximos_pedidos
			FROM zonas
			WHERE zonas.tipo_zona_id = 1
			ORDER BY zonas.nombre ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerEnviadosResumenZonaFechas($zonaId, $fechaInicial, $fechaFinal)
	{
		//Muestra un total GENERAL de los mensajes enviados en el módulo de aviso a unidades y próximos pedidos por ZONA.
		$sql = $this->base_datos->query("SELECT zonas.idzona as zona_id, 
			UPPER(zonas.nombre) as zona_nombre,
			(SELECT COUNT(idsmsenviado) FROM sms_enviados 
			WHERE sms_enviados.zona_id = zonas.idzona 
			AND sms_enviados.modulo_envio_sms_id = 1
			AND sms_enviados.fecha >= '$fechaInicial'
			AND sms_enviados.fecha <= '$fechaFinal') AS total_pedidos,
			(SELECT COUNT(idsmsenviado) FROM sms_enviados 
			WHERE sms_enviados.zona_id = zonas.idzona 
			AND sms_enviados.modulo_envio_sms_id = 2
			AND sms_enviados.fecha >= '$fechaInicial'
			AND sms_enviados.fecha <= '$fechaFinal') AS total_proximos_pedidos
			FROM zonas
			WHERE zonas.idzona = '$zonaId'
			ORDER BY zonas.nombre ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertar($pedido_id,$ruta_id, $cliente_id, $tipo_direccion_sms_id, $estatus_sms_id, $zona_id, $contenido, $fecha, $modulo_envio_sms_id)
	{
		$this->base_datos->insert("sms_enviados", [
			"pedido_id" => $pedido_id,
			"ruta_id" => $ruta_id,
			"cliente_id" => $cliente_id,
			"tipo_direccion_sms_id" => $tipo_direccion_sms_id,
			"estatus_sms_id" => $estatus_sms_id,
			"zona_id" => $zona_id,
			"contenido" => $contenido,
			"fecha" => $fecha,
			"modulo_envio_sms_id" => $modulo_envio_sms_id
		]);
		return $this->base_datos->id();
	}

	function actualizar($id,$pedido_id,$ruta_id, $cliente_id, $tipo_direccion_sms, $estatus_sms_id, $zona_id, $contenido, $fecha, $modulo_envio_sms_id)
	{
		$this->base_datos->update("sms_enviados", [
			"pedido_id" => $pedido_id,
			"ruta_id" => $ruta_id,
			"cliente_id" => $cliente_id,
			"tipo_direccion_sms" => $tipo_direccion_sms,
			"estatus_sms_id" => $estatus_sms_id,
			"zona_id" => $zona_id,
			"contenido" => $contenido,
			"fecha" => $fecha,
			"modulo_envio_sms_id" => $modulo_envio_sms_id

		], ["idsmsenviado[=]" => $id]);
	}

	function eliminar($id)
	{
		$this->base_datos->delete("sms_enviados", ["idsmsenviado[=]" => $id]);
	}
}
