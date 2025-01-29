<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelGasto
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenerGastosAdministrativosZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			origenes_gasto.nombre AS origen_gasto,conceptos_gasto.nombre AS concepto_gasto,
			gastos.cantidad,gastos.observaciones,gastos.zona_id
			FROM gastos,origenes_gasto,conceptos_gasto
			WHERE gastos.origen_gasto_id = origenes_gasto.idorigengasto
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND tipo_gasto_id = 1 
			AND zona_id = '$zonaId' ORDER BY anio,mes ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastosAdministrativosZonaEntreFechas($zonaId, $mesInicial, $anioInicial, $mesFinal, $anioFinal)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			origenes_gasto.nombre AS origen_gasto,conceptos_gasto.nombre AS concepto_gasto,
			gastos.cantidad,gastos.observaciones,gastos.zona_id,zonas.nombre AS zona_nombre
			FROM gastos,origenes_gasto,conceptos_gasto,zonas
			WHERE gastos.origen_gasto_id = origenes_gasto.idorigengasto
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND gastos.zona_id = zonas.idzona
			AND tipo_gasto_id = 1 
			AND zona_id = '$zonaId' 
			AND (mes >= '$mesInicial' AND anio >= '$anioInicial')
			AND (mes <= '$mesFinal' AND anio <= '$anioFinal')
			ORDER BY anio,mes ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastosRutasZona($zonaId)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			rutas.clave_ruta AS ruta_gasto,conceptos_gasto.nombre AS concepto_gasto,
			gastos.cantidad,gastos.observaciones,gastos.zona_id
			FROM gastos,rutas,conceptos_gasto
			WHERE gastos.ruta_id = rutas.idruta
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND tipo_gasto_id = 2 
			AND gastos.zona_id = '$zonaId' ORDER BY anio,mes ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastosRutasZonaEntreFechas($zonaId, $mesInicial, $anioInicial, $mesFinal, $anioFinal)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			rutas.clave_ruta AS ruta_gasto,conceptos_gasto.nombre AS concepto_gasto,
			gastos.cantidad,gastos.observaciones,gastos.zona_id,zonas.nombre as zona_nombre
			FROM gastos,rutas,conceptos_gasto,zonas
			WHERE gastos.ruta_id = rutas.idruta
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND zonas.idzona = gastos.zona_id
			AND tipo_gasto_id = 2 
			AND gastos.zona_id = '$zonaId' 
			AND (mes >= '$mesInicial' AND anio >= '$anioInicial')
			AND (mes <= '$mesFinal' AND anio <= '$anioFinal')
			ORDER BY anio,mes ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastosRutasZonaRutaEntreFechas($zonaId,$rutaId, $mesInicial, $anioInicial, $mesFinal, $anioFinal)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			rutas.clave_ruta AS ruta_gasto,conceptos_gasto.nombre AS concepto_gasto,
			gastos.cantidad,gastos.observaciones,gastos.zona_id,zonas.nombre as zona_nombre
			FROM gastos,rutas,conceptos_gasto,zonas
			WHERE gastos.ruta_id = rutas.idruta
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND zonas.idzona = gastos.zona_id
			AND tipo_gasto_id = 2 
			AND gastos.ruta_id = '$rutaId' 
			AND gastos.zona_id = '$zonaId' 
			AND (mes >= '$mesInicial' AND anio >= '$anioInicial')
			AND (mes <= '$mesFinal' AND anio <= '$anioFinal')
			ORDER BY anio,mes ASC")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastoAdministrativoId($id)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			origenes_gasto.idorigengasto AS origen_gasto_id,conceptos_gasto.idconceptogasto AS concepto_gasto_id,
			gastos.cantidad,gastos.observaciones,gastos.zona_id
			FROM gastos,origenes_gasto,conceptos_gasto
			WHERE gastos.origen_gasto_id = origenes_gasto.idorigengasto
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND tipo_gasto_id = 1 
			AND gastos.idgasto = '$id'")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerGastoRutaId($id)
	{
		$sql = $this->base_datos->query("SELECT gastos.idgasto,gastos.mes,gastos.anio,gastos.fecha,
			rutas.idruta AS ruta_gasto_id,conceptos_gasto.idconceptogasto AS concepto_gasto_id,
			gastos.cantidad,gastos.observaciones,gastos.zona_id
			FROM gastos,rutas,conceptos_gasto
			WHERE gastos.ruta_id = rutas.idruta
			AND gastos.concepto_gasto_id = conceptos_gasto.idconceptogasto
			AND tipo_gasto_id = 2 
			AND gastos.idgasto = '$id'")
			->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function agregarGastoAdministrativo($mes, $anio, $origen, $concepto, $cantidad, $observaciones, $zona, $zonaId)
	{
		$this->base_datos->insert("gastos", [
			"mes" => $mes,
			"anio" => $anio,
			"tipo_gasto_id" => 1,
			"origen_gasto_id" => $origen,
			"concepto_gasto_id" => $concepto,
			"cantidad" => $cantidad,
			"observaciones" => $observaciones,
			"zona_id" => $zonaId
		]);
		return $this->base_datos->id();
	}

	public function agregarGastoRuta($mes, $anio, $rutaId, $concepto, $cantidad, $observaciones, $zonaId, $comprobante) {
		echo "Valores recibidos:<br>";
		echo "Mes: $mes<br>";
		echo "Año: $anio<br>";
		echo "Ruta: $rutaId<br>";
		echo "Concepto: $concepto<br>";
		echo "Cantidad: $cantidad<br>";
		echo "Observaciones: $observaciones<br>";
		echo "Zona: $zonaId<br>";
		echo "Comprobante: $comprobante<br>"; // Verificar que no sea "2"
	
		$this->base_datos->insert("gastos", [
			"mes" => $mes,
			"anio" => $anio,
			"tipo_gasto_id" => 2, // Aquí no se debe modificar
			"ruta_id" => $rutaId,
			"concepto_gasto_id" => $concepto,
			"cantidad" => $cantidad,
			"observaciones" => $observaciones,
			"zona_id" => $zonaId,
			"comprobante_gasto" => $comprobante
		]);
	
		return $this->base_datos->id();
	}
	

	function actualizarGastoAdministrativo($id, $mes, $anio, $origen, $concepto, $cantidad, $observaciones, $zona, $zonaId)
	{
		$this->base_datos->update("gastos", [
			"mes" => $mes,
			"anio" => $anio,
			"tipo_gasto_id" => 1,
			"origen_gasto_id" => $origen,
			"concepto_gasto_id" => $concepto,
			"cantidad" => $cantidad,
			"observaciones" => $observaciones,
			"zona_id" => $zonaId

		], ["idgasto[=]" => $id]);
	}

	function actualizarGastoRuta($id, $mes, $anio, $rutaId, $concepto, $cantidad, $observaciones, $zonaId)
	{
		$this->base_datos->update("gastos", [
			"mes" => $mes,
			"anio" => $anio,
			"tipo_gasto_id" => 2,
			"ruta_id" => $rutaId,
			"concepto_gasto_id" => $concepto,
			"cantidad" => $cantidad,
			"observaciones" => $observaciones,
			"zona_id" => $zonaId

		], ["idgasto[=]" => $id]);
	}

	function eliminarGasto($id)
	{
		$this->base_datos->delete("gastos", ["idgasto[=]" => $id]);
	}

	function listaTiposGasto()
	{
		$sql = $this->base_datos->select("tipos_gasto",["idtipogasto","nombre"]);
		return $sql;
	}
}
