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
    // Verificar si se subió un archivo de comprobante
    if (isset($comprobante) && $comprobante['error'] == UPLOAD_ERR_OK) {
        // Directorio donde se guardará el archivo
        $target_dir = "../view/autoconsumos/comprobantes/";
        
        // Verificar que el directorio exista
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true); // Crear el directorio si no existe
        }

        // Nombre del archivo (se puede renombrar para evitar colisiones)
        $target_file = $target_dir . basename($comprobante["name"]);

        // Validar el tamaño del archivo (ejemplo: máximo 2MB)
        if ($comprobante["size"] > 2000000) {
            throw new Exception("El archivo es demasiado grande. Tamaño máximo: 2MB.");
        }

        // Validar el tipo de archivo (ejemplo: solo imágenes)
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($comprobante["type"], $allowed_types)) {
            throw new Exception("Solo se permiten archivos JPEG, PNG o PDF.");
        }

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($comprobante["tmp_name"], $target_file)) {
            // Si se subió correctamente, insertar los datos en la base de datos
            $sql = $this->base_datos->insert("autoconsumos", [
                "fechai" => $fechaInicio,
                "fechaf" => $fechaFin,
                "litros" => $litros,
                "costo" => $costo_litro,
                "km_ini" => $kmi,
                "km_fin" => $kmf,
                "ruta_id" => $rutaId,
                "combustible" => $combustible,
                "comprobante_autoconsumo" => $target_file // Guardar la ruta del comprobante
            ]);
            return $this->base_datos->id(); // Retornar el ID de la inserción
        } else {
            // Manejar el error si no se pudo mover el archivo
            throw new Exception("Error al subir el comprobante.");
        }
    } else {
        // Manejar el caso en que no se subió un archivo
        throw new Exception("Debes subir un comprobante.");
    }
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
                                    AND a.combustible ='$productoNombre'
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerAutoconsumosZonaFecha($zonaId, $fechaInicial, $fechaFinal)
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
                                    AND zonas.idzona ='$zonaId' 
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerAutoconsumosZonaProductoFecha($zonaId, $productoNombre, $fechaInicial, $fechaFinal)
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
                                    AND zonas.idzona ='$zonaId' 
                                    AND a.combustible ='$productoNombre'
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerTotalAutoconsumosZonaProductoFecha($zonaId, $productoNombre, $fechaInicial, $fechaFinal)
  {
    $sql = $this->base_datos->query("SELECT SUM(a.litros) AS total
                                    FROM autoconsumos a,rutas r,zonas,companias
                                    WHERE a.ruta_id=r.idruta 
                                    AND r.zona_id = zonas.idzona
                                    AND zonas.compania_id = companias.idcompania
                                    AND zonas.idzona ='$zonaId' 
                                    AND a.combustible ='$productoNombre'
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerAutoconsumosRutaFecha($rutaId, $fechaInicial, $fechaFinal)
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
                                    AND r.idruta ='$rutaId' 
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
                                    ORDER BY a.fechai")->fetchAll(PDO::FETCH_ASSOC);
    return $sql;
  }

  function obtenerAutoconsumosRutaProductoFecha($rutaId, $productoNombre, $fechaInicial, $fechaFinal)
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
                                    AND r.idruta ='$rutaId' 
                                    AND a.combustible ='$productoNombre'
                                    AND (a.fechai between '$fechaInicial' and '$fechaFinal') 
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
