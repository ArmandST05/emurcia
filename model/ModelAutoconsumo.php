<?php
include_once('Medoo.php');

use Medoo\Medoo;

/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelAutoconsumo
{

  var $base_datos; //Variable para hacer la conexion a la base de datos
  var $resultado; //Variable para traer resultados de una consulta a la BD

  function __construct()
  { //Constructor de la conexion a la BD
    $this->base_datos = new Medoo();
  }

  function insertar($rutaId, $combustible, $litros, $costo_litro, $kmi, $kmf, $fechaInicio, $fechaFin, $comprobante)
  {
    $sql = $this->base_datos->insert("autoconsumos", [
      "fechai" => $fechaInicio,
      "fechaf" => $fechaFin,
      "litros" => $litros,
      "costo" => $costo_litro,
      "km_ini" => $kmi,
      "km_fin" => $kmf,
      "ruta_id" => $rutaId,
      "combustible" => $combustible,
      "comprobante_autoconsumo" => $comprobante
    ]);
    return $this->base_datos->id();
  }

  function insertar_AutoconcumoEstaciones($rutaId, $litros, $costo_litro, $fecha, $comprobante){
    $sql = $this->base_datos->insert("autoconsumos_estaciones", [
        "ruta_id" => $rutaId,
        "litros" => $litros,
        "costo" => $costo_litro,
        "fecha" => $fecha,
        "comprobante_estaciones" => $comprobante

    ]);
    return $this->base_datos->id();
  }
  function obtenerEstacionesPorZona($zonaId, $tipoRutaId = 5) {
    $sql = $this->base_datos->query("
        SELECT idruta, clave_ruta 
        FROM rutas 
        WHERE zona_id = '$zonaId' AND tipo_ruta_id = $tipoRutaId
    ");
    return $sql->fetchAll(PDO::FETCH_ASSOC);
}


  function obtenerAutoconsumosCompaniaFecha($companiaId, $fechaInicial, $fechaFinal)
  {
    $sql = $this->base_datos->query("SELECT companias.idcompania AS compania_id,UPPER(companias.nombre) AS compania_nombre,
                                    zonas.idzona AS zona_id, UPPER(zonas.nombre) AS zona_nombre, 
                                    r.clave_ruta AS ruta_nombre, r.idruta AS ruta_id,
                                    a.idautoconsumo,a.fechai,a.fechaf,a.combustible,a.litros,
                                    a.costo,(a.litros*a.costo)total,a.km_ini,a.km_fin,
                                    FORMAT(((a.km_fin-a.km_ini)/a.litros),2)rendimiento
                                    FROM autoconsumos a,rutas r,zonas,companias
                                    WHERE a.ruta_id=r.idruta
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND companias.idcompania ='$companiaId' 
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }
  function obtenerAutoconsumosCompaniaProductoFecha($companiaId, $productoNombre, $fechaInicial, $fechaFinal)
  {
      $sql = $this->base_datos->query("SELECT 
                                      companias.idcompania AS compania_id,
                                      UPPER(companias.nombre) AS compania_nombre,
                                      zonas.idzona AS zona_id, 
                                      UPPER(zonas.nombre) AS zona_nombre, 
                                      r.clave_ruta AS ruta_nombre, 
                                      r.idruta AS ruta_id,
                                      a.idautoconsumo,
                                      a.fechai,
                                      a.fechaf,
                                      a.combustible,
                                      a.litros,
                                      a.costo,
                                      (a.litros * a.costo) AS total,
                                      a.km_ini,
                                      a.km_fin,
                                      FORMAT(((a.km_fin - a.km_ini) / a.litros), 2) AS rendimiento,
                                      a.comprobante_autoconsumo  -- Agregado aquí
                                      FROM autoconsumos a, rutas r, zonas, companias
                                      WHERE a.ruta_id = r.idruta 
                                      AND r.zona_id = zonas.idzona
                                      AND zonas.compania_id = companias.idcompania
                                      AND companias.idcompania = '$companiaId' 
                                      AND a.combustible = '$productoNombre'
                                      AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal') 
                                      ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
      return $sql;
  }
  function obtenerAutoconsumosZonaFecha($zonaId, $fechaInicial, $fechaFinal)
{
    $sql = $this->base_datos->query("SELECT 
                                    companias.idcompania AS compania_id,
                                    UPPER(companias.nombre) AS compania_nombre,
                                    zonas.idzona AS zona_id, 
                                    UPPER(zonas.nombre) AS zona_nombre, 
                                    r.clave_ruta AS ruta_nombre, 
                                    r.idruta AS ruta_id,
                                    a.idautoconsumo,
                                    a.fechai,
                                    a.fechaf,
                                    a.combustible,
                                    a.litros,
                                    a.costo,
                                    (a.litros * a.costo) AS total,
                                    a.km_ini,
                                    a.km_fin,
                                    FORMAT(((a.km_fin - a.km_ini) / a.litros), 2) AS rendimiento,
                                    a.comprobante_autoconsumo  -- Agregado aquí
                                    FROM autoconsumos a, rutas r, zonas, companias
                                    WHERE a.ruta_id = r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND zonas.idzona = '$zonaId' 
                                    AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
}

function obtenerAutoconsumosZonaProductoFecha($zonaId, $productoNombre, $fechaInicial, $fechaFinal)
{
    $sql = $this->base_datos->query("SELECT 
                                    companias.idcompania AS compania_id,
                                    UPPER(companias.nombre) AS compania_nombre,
                                    zonas.idzona AS zona_id, 
                                    UPPER(zonas.nombre) AS zona_nombre, 
                                    r.clave_ruta AS ruta_nombre, 
                                    r.idruta AS ruta_id,
                                    a.idautoconsumo,
                                    a.fechai,
                                    a.fechaf,
                                    a.combustible,
                                    a.litros,
                                    a.costo,
                                    (a.litros * a.costo) AS total,
                                    a.km_ini,
                                    a.km_fin,
                                    FORMAT(((a.km_fin - a.km_ini) / a.litros), 2) AS rendimiento,
                                    a.comprobante_autoconsumo  -- Agregado aquí
                                    FROM autoconsumos a, rutas r, zonas, companias
                                    WHERE a.ruta_id = r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND zonas.idzona = '$zonaId' 
                                    AND a.combustible = '$productoNombre'
                                    AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
}

function obtenerTotalAutoconsumosZonaProductoFecha($zonaId, $productoNombre, $fechaInicial, $fechaFinal)
{
    $sql = $this->base_datos->query("SELECT SUM(a.litros) AS total
                                    FROM autoconsumos a, rutas r, zonas, companias
                                    WHERE a.ruta_id = r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND zonas.idzona = '$zonaId' 
                                    AND a.combustible = '$productoNombre'
                                    AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal')")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
}
function obtenerTotalAutoconsumosEstacionesProductoFecha($productoNombre, $fechaInicial, $fechaFinal, $rutaId)
{
    $sql = $this->base_datos->query("
        SELECT IFNULL(SUM(a.litros), 0) AS total
        FROM autoconsumos_estaciones a
        INNER JOIN rutas r ON a.ruta_id = r.idruta
        WHERE r.tipo_ruta_id = 5
          AND r.idruta = '$rutaId'
          AND a.fecha BETWEEN '$fechaInicial' AND '$fechaFinal'
    ")->fetchAll(PDO::FETCH_ASSOC);

    return $sql;
}



function obtenerAutoconsumosRutaFecha($rutaId, $fechaInicial, $fechaFinal)
{
    $sql = $this->base_datos->query("SELECT 
                                    companias.idcompania AS compania_id,
                                    UPPER(companias.nombre) AS compania_nombre,
                                    zonas.idzona AS zona_id, 
                                    UPPER(zonas.nombre) AS zona_nombre, 
                                    r.clave_ruta AS ruta_nombre, 
                                    r.idruta AS ruta_id,
                                    a.idautoconsumo,
                                    a.fechai,
                                    a.fechaf,
                                    a.combustible,
                                    a.litros,
                                    a.costo,
                                    (a.litros * a.costo) AS total,
                                    a.km_ini,
                                    a.km_fin,
                                    FORMAT(((a.km_fin - a.km_ini) / a.litros), 2) AS rendimiento,
                                    a.comprobante_autoconsumo  -- Agregado aquí
                                    FROM autoconsumos a, rutas r, zonas, companias
                                    WHERE a.ruta_id = r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND r.idruta = '$rutaId' 
                                    AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
}

function obtenerAutoconsumosRutaProductoFecha($rutaId, $productoNombre, $fechaInicial, $fechaFinal)
{
    $sql = $this->base_datos->query("SELECT 
                                    companias.idcompania AS compania_id,
                                    UPPER(companias.nombre) AS compania_nombre,
                                    zonas.idzona AS zona_id, 
                                    UPPER(zonas.nombre) AS zona_nombre, 
                                    r.clave_ruta AS ruta_nombre, 
                                    r.idruta AS ruta_id,
                                    a.idautoconsumo,
                                    a.fechai,
                                    a.fechaf,
                                    a.combustible,
                                    a.litros,
                                    a.costo,
                                    (a.litros * a.costo) AS total,
                                    a.km_ini,
                                    a.km_fin,
                                    FORMAT(((a.km_fin - a.km_ini) / a.litros), 2) AS rendimiento,
                                    a.comprobante_autoconsumo  -- Agregado aquí
                                    FROM autoconsumos a, rutas r, zonas, companias
                                    WHERE a.ruta_id = r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND r.idruta = '$rutaId' 
                                    AND a.combustible = '$productoNombre'
                                    AND (a.fechai BETWEEN '$fechaInicial' AND '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
}


  function eliminar($id)
  {
    $this->base_datos->delete("autoconsumos", ["idautoconsumo[=]" => $id]);
  }

  function obtenerUltimoKilometrajeRutaProducto($rutaId,$productoNombre)
	{
		$sql = $this->base_datos->query("SELECT km_fin 
			FROM autoconsumos 
			WHERE ruta_id = '$rutaId' 
			AND combustible = '$productoNombre'
			ORDER BY fechai DESC LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
}
