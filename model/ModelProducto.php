<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelProducto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function index()
	{
		$sql = $this->base_datos->query("SELECT * FROM productos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerCilindros()
	{	//Productos id = 1 Lts
		//Los litros tienen id = 4
		$sql = $this->base_datos->query("SELECT * FROM productos 
		WHERE tipo_producto_id = 1 
		AND idproducto != 4 ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function productosPorRuta($rutaId,$tipoZonaId)
	{
		$sql = $this->base_datos->query("SELECT productos.idproducto, productos.nombre 
				FROM productos, rutas, tipos_ruta, tipo_ruta_productos
				WHERE rutas.tipo_ruta_id = tipos_ruta.idtiporuta AND
				tipos_ruta.idtiporuta = tipo_ruta_productos.tipo_ruta_id
				AND tipo_ruta_productos.producto_id = productos.idproducto
				AND productos.tipo_producto_id = '$tipoZonaId' 
				AND rutas.idruta = '$rutaId'
				ORDER BY productos.nombre
			")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function productosPorZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM productos
				WHERE productos.tipo_producto_id = (SELECT tipo_zona_id FROM zonas WHERE idzona = '$zonaId')
				ORDER BY productos.nombre
			")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function productosPorTipoZona($tipoZonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM productos
				WHERE productos.tipo_producto_id = $tipoZonaId
				ORDER BY productos.nombre
			")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function productoPorId($id)
	{
		$sql = $this->base_datos->query("SELECT * FROM productos WHERE idproducto = '$id'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaProductosPedidos()
	{
		//Obtiene la lista de productos que están disponibles para que desde la sección de pedidos se seleccionen
		$sql = $this->base_datos->query("SELECT productos.idproducto, productos.nombre FROM productos
				WHERE productos.activo_pedido_web = 1
				ORDER BY productos.nombre
			")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	/*--------------PRECIOS POR PRODUCTO--------------- */
	
	function obtenerPrecioMesProducto($anio,$mes,$zonaId,$rutaId,$productoId)
	{
		$sql = $this->base_datos->query("SELECT precio FROM producto_precios
				WHERE anio = '$anio' 
				AND mes = '$mes'
				AND zona_id = '$zonaId'
				AND ruta_id = '$rutaId'
				AND producto_id = '$productoId'
				ORDER BY idproductoprecio DESC
				LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertarPrecioProductoZona($zonaId,$rutaId,$mes,$anio,$productoId,$precio){
		$this->base_datos->insert("producto_precios",[
			"mes" => $mes,
			"anio" => $anio,
			"zona_id" => $zonaId,
			"ruta_id" => $rutaId,
			"producto_id" => $productoId,
			"precio" => $precio
		]);
		return $this->base_datos->id();
	}
}
