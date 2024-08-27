<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelDesviacionVolumen
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{
		//Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function insertar($fecha, $zonaId, $rutaId, $productoId, $facturaRemision, $volumenFactura, $proveedorId, $transporte, $tanqueDescarga, $volumenDescargaBruto, $ventaDescarga, $inventarioInicial, $inventarioFinal,$comprasDia,$totalVendidoSistema)
	{
		$this->base_datos->insert("volumen_desviaciones", [
			"fecha" => $fecha,
			"zona_id" => $zonaId,
			"ruta_id" => $rutaId,
			"producto_id" => $productoId,
			"factura_remision" => $facturaRemision,
			"volumen_factura" => $volumenFactura,
			"proveedor_id" => $proveedorId,
			"transporte" => $transporte,
			"tanque_descarga" => $tanqueDescarga,
			"volumen_descarga_bruto" => $volumenDescargaBruto,
			"venta_descarga" => $ventaDescarga,
			"inventario_inicial" => $inventarioInicial,
			"inventario_final" => $inventarioFinal,
			"compras_dia" => $comprasDia,
			"total_vendido_sistema" => $totalVendidoSistema
		]);
		return $this->base_datos->id();
	}
	
	function listaPorZonaRutaFechas($zonaId,$rutaId,$productoId,$fechaInicial,$fechaFinal)
	{
		$sql = $this->base_datos->query("SELECT volumen_desviaciones.*,proveedores.nombre AS proveedor_nombre
		 FROM volumen_desviaciones 
		LEFT JOIN proveedores ON volumen_desviaciones.proveedor_id = proveedores.idproveedor
		 WHERE volumen_desviaciones.zona_id = '$zonaId' 
		 AND volumen_desviaciones.ruta_id = '$rutaId'
		 AND volumen_desviaciones.producto_id = '$productoId'
		 AND volumen_desviaciones.fecha >= '$fechaInicial'
		 AND volumen_desviaciones.fecha <= '$fechaFinal'
		")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function validarPorRutaProductoFecha($rutaId, $productoId, $fecha)
	{
		$sql = $this->base_datos->select("volumen_desviaciones", "*", [
			"ruta_id[=]" => $rutaId,
			"producto_id[=]" => $productoId,
			"fecha[=]" => $fecha
		], ["ORDER" => ["fecha" => "DESC"]]);
		return $sql;
	}
	
	function eliminar($id)
	{
	  $this->base_datos->delete("volumen_desviaciones", ["idvolumendesviacion[=]" => $id]);
	}
}
