<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCompraGasolina
{

	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerZonaProducto($zonaId,$productoId,$fecha){
		$sql = $this->base_datos->query("SELECT cg.*,p.nombre as producto_nombre,z.nombre as zona_nombre
		FROM compras_gasolina cg 
		INNER JOIN productos p ON p.idproducto = cg.producto_id
		INNER JOIN zonas z ON z.idzona = cg.zona_id
		WHERE cg.fecha BETWEEN DATE_FORMAT('$fecha','%Y-%m-01') 
		AND LAST_DAY('$fecha') 
		AND cg.zona_id = '$zonaId' 
		AND cg.producto_id = '$productoId'
		ORDER BY cg.fecha ASC")->fetchall();
		return $sql;
	}

	function obtenerTotalZonaProductoFechaDescarga($zonaId,$productoId,$fechaDescarga){
		$sql = $this->base_datos->query("SELECT SUM(litros) AS total_litros FROM compras_gasolina 
		WHERE fecha_descarga = '$fechaDescarga'
		AND zona_id = '$zonaId' 
		AND producto_id = '$productoId'
		ORDER BY fecha ASC")->fetchall();
		return $sql;
	}

	function insertarCompraGasolina($fecha,$productoId, $numFactura, $chofer, $litros,$zonaId, $aceite, $precio, $importe, $fechaDescarga, $fechaPago, $tarifa)
	{
		$this->base_datos->insert("compras_gasolina", [
			"fecha" => $fecha,
			"producto_id" => $productoId,
			"num_factura" => $numFactura,
			"chofer" => $chofer,
			"litros" =>  $litros,
			"zona_id" =>  $zonaId,
			"aceite" => $aceite,
			"precio" => $precio,
			"importe" => $importe,
			"fecha_descarga" => $fechaDescarga,
			"fecha_pago" => $fechaPago,
			"tarifa" => $tarifa
		]);
		return $this->base_datos->id();
	}

	function eliminar($id)
	{
		$this->base_datos->delete("compras_gasolina", ["idcompragasolina[=]" => $id]);
	}

	function obtenerAceitesZonaId($zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM aceites WHERE zona_id='$zonaId'");
		return $sql;
	}
}
