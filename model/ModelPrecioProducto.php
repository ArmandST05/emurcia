<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelPrecioProducto{
	
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct() { //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	/*----------------PRECIOS POR ZONA/RUTA--------------*/

	function insertarPrecioGasMesZona($mes,$anio,$zonaId,$rutaId,$precioKilo,$precioLitro){
		$this->base_datos->insert("precios_gas" ,[
			"precio_kilo" => $precioKilo,
			"precio_litro" => $precioLitro,
			"mes" => $mes,
			"anio" => $anio,
			"zona_id" => $zonaId,
			"ruta_id" => $rutaId
			]);
		return $this->base_datos->id();
	}

	function actualizarPrecioGasMesZona($mes,$anio,$zonaId,$rutaId,$precioKilo,$precioLitro){
		$this->base_datos->update("precios_gas" ,[
			"precio_kilo" => $precioKilo,
			"precio_litro" => $precioLitro
			],["AND" => ["mes" => $mes , "anio" => $anio, "zona_id" => $zonaId,"ruta_id" => $rutaId]]);
	}

	function obtenerPrecioGasZonaMes($anio,$mes,$zonaId,$rutaId)
	{
		$sql = $this->base_datos->query("SELECT precio_kilo,precio_litro 
				FROM precios_gas
				WHERE anio = '$anio' 
				AND mes = '$mes'
				AND zona_id = '$zonaId'
				AND ruta_id = '$rutaId'
				ORDER BY idpreciogas DESC
				LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerPrecioGasZonasMes($mes,$anio){
		//Obtiene el último precio general del mes registrado por zona
		$sql = $this->base_datos->query("SELECT preciomes1.*,zonas.nombre AS zona_nombre 
			FROM precios_gas preciomes1
			INNER JOIN (
				SELECT MAX(idpreciogas) max_id,zona_id
				FROM precios_gas
				WHERE mes = '$mes'
				AND anio = '$anio'
				AND ruta_id = 0
				GROUP BY zona_id
			) preciomes2
			ON preciomes1.zona_id = preciomes2.zona_id
			AND preciomes1.idpreciogas = preciomes2.max_id
			INNER JOIN zonas ON zonas.idzona = preciomes1.zona_id
			ORDER BY zonas.nombre;")->fetchAll();
		return $sql;
	}

	function obtenerPrecioGasRutasZonaMes($mes,$anio,$zonaId){
		//Obtiene el último precio general del mes registrado por zona
		$sql = $this->base_datos->query("SELECT preciomes1.*,rutas.clave_ruta AS ruta_nombre 
			FROM precios_gas preciomes1
			INNER JOIN (
				SELECT MAX(idpreciogas) max_id,ruta_id
				FROM precios_gas
				WHERE mes = '$mes'
				AND anio = '$anio'
				AND zona_id = '$zonaId'
				GROUP BY ruta_id
			) preciomes2
			ON preciomes1.ruta_id = preciomes2.ruta_id
			AND preciomes1.idpreciogas = preciomes2.max_id
			INNER JOIN rutas ON rutas.idruta = preciomes1.ruta_id
			ORDER BY rutas.clave_ruta;")->fetchAll();
		return $sql;
	}

	/*-------------PRECIOS GASOLINA---------------- */
	function insertarPrecioMesGasolinaProducto($fecha,$precio,$zonaId,$ieps,$productoId){
		$this->base_datos->insert("precios_gasolina",[
			"fecha" => $fecha,
			"precio" => $precio,
			"zona_id" => $zonaId,
			"ieps" => $ieps,
			"producto_id" => $productoId,
		]);
		return $this->base_datos->id();
	}

	function obtenerPrecioGasolinaZonaProductoId($zonaId,$productoId)
	{
		$sql = $this->base_datos->query("SELECT idpreciogasolina,precio 
		FROM precios_gasolina 
		WHERE zona_id = '$zonaId' AND producto_id='$productoId' 
		ORDER BY idpreciogasolina DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);

		return $sql;
	}
}