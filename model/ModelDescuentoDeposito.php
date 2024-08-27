<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelDescuentoDeposito {
	
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct(){ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaZonaFecha($zonaId,$fecha){
		//Obtiene los descuentos-depósitos de una zona en cierta fecha
		$sql = $this->base_datos->query("SELECT * FROM descuentos_deposito
		WHERE fecha = '$fecha'
		AND zona_id = '$zonaId'
		ORDER BY fecha ASC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function listaZonaEntreFechas($zonaId,$fechaInicial,$fechaFinal){
		//Obtiene los descuentos-depósitos de una zona en un rango de fechas
		$sql = $this->base_datos->query("SELECT * FROM descuentos_deposito
		WHERE fecha >= '$fechaInicial' 
		AND fecha <= '$fechaFinal'
		AND zona_id = '$zonaId'
		ORDER BY fecha ASC")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}

	function insertar($fecha,$zonaId,$pagoElectronico,$valeRetiro,$descripcionValeRetiro,$gastos,$cheque,$otrasSalidas)
	{
		$this->base_datos->insert("descuentos_deposito", [
			"fecha" => $fecha,
			"zona_id" => $zonaId,
			"pago_electronico" => $pagoElectronico,
			"vale_retiro" => $valeRetiro,
			"descripcion_vale_retiro" => $descripcionValeRetiro,
			"gastos" => $gastos,
			"cheque" => $cheque,
			"otras_salidas" => $otrasSalidas
		]);
		
		return $this->base_datos->id();
	}

	function eliminar($id){
		$this->base_datos->delete("descuentos_deposito",["iddescuentodeposito[=]" => $id]);
	}
}
