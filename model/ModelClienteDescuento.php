<?php
include_once('Medoo.php');

use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelClienteDescuento
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD
	var $tabla = "clientes_descuento";
	var $tablaVentas = "venta_cliente_descuentos";

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function listaZonaEstatus($zonaId, $estatusId = 1)
	{
		$sql = $this->base_datos->query("SELECT cd.*,d.cantidad AS descuento_cantidad
			FROM $this->tabla cd 
			INNER JOIN descuentos d ON d.iddescuento = cd.descuento_id
			WHERE zona_id='$zonaId'
			AND estatus = '$estatusId'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function insertar($nombre, $giro, $calle, $numero, $colonia, $municipio, $zonaId, $descuentoId)
	{
		$this->base_datos->insert($this->tabla, [
			"nombre" => $nombre,
			"giro" => $giro,
			"calle" => $calle,
			"numero" => $numero,
			"colonia" => $colonia,
			"municipio" => $municipio,
			"zona_id" => $zonaId,
			"descuento_id" => $descuentoId
		]);
		return $this->base_datos->id();
	}

	//Insertar detalle de descuento de cliente en la venta
	function insertarDetalleVenta($ventaId, $clienteDescuentoId, $descuentoId, $cantidad, $total)
	{
		$this->base_datos->insert($this->tablaVentas, [
			"detalle_venta_id" => $ventaId,
			"cliente_descuento_id" => $clienteDescuentoId,
			"descuento_id" => $descuentoId,
			"cantidad" => $cantidad,
			"total" => $total
		]);
		return $this->base_datos->id();
	}

	function verificarNombre($nombre, $zonaId)
	{
		$sql = $this->base_datos->query("SELECT * FROM $this->tabla 
		WHERE nombre='" . $nombre . "' AND `zona_id`='" . $zonaId . "' ")->fetchAll();
		return $sql;
	}

	function actualizar($id, $nombre, $giro, $calle, $numero, $colonia, $municipio, $zonaId, $descuentoId)
	{
		$this->base_datos->update($this->tabla, [
			"nombre" => $nombre,
			"giro" => $giro,
			"calle" => $calle,
			"numero" => $numero,
			"colonia" => $colonia,
			"municipio" => $municipio,
			"zona_id" => $zonaId,
			"descuento_id" => $descuentoId
		], ["idclientedescuento[=]" => $id]);
	}

	function actualizarEstatus($id, $estatusId)
	{
		return $this->base_datos->update($this->tabla, [
			"estatus" => $estatusId
		], ["idclientedescuento[=]" => $id]);
	}

	function eliminar($id)
	{
		$this->base_datos->delete($this->tabla, ["idclientedescuento[=]" => $id]);
	}

	function eliminarPorDetalleVenta($id)
	{
		$this->base_datos->delete($this->tablaVentas, ["detalle_venta_id[=]" => $id]);
	}

	function obtenerPorId($id)
	{
		$sql = $this->base_datos->query("SELECT * FROM $this->tabla
			WHERE idclientedescuento = '$id'")->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function obtenerLike($nombre, $zonaId)
	{
		$sql = $this->base_datos->select($this->tabla, '*', [
			"AND #dos opciones" => [
				"OR #Una opcion u otra" => ["nombre[~]" => $nombre],
				"AND #checamos zona" => ["zona_id" => $zonaId]
			]
		]);
		return $sql;
	}

	function listaZonaFechaZonaRutaProducto($fechaInicial, $fechaFinal, $zonaId, $rutaId = 0, $productoId = 0, $clienteDescuentoId = 0)
	{
		$sqlQuery = "SELECT v.fecha,r.clave_ruta AS ruta_nombre,
			p.nombre AS producto_nombre,cd.nombre AS cliente_nombre,
			d.cantidad AS descuento_cantidad,
			vcd.cantidad,vcd.total
			FROM $this->tablaVentas vcd 
			INNER JOIN $this->tabla cd ON cd.idclientedescuento = vcd.cliente_descuento_id
			INNER JOIN descuentos d ON d.iddescuento = vcd.descuento_id
			INNER JOIN detalles_venta dv ON dv.iddetalleventa = vcd.detalle_venta_id
			INNER JOIN ventas v ON v.idventa = dv.venta_id
			INNER JOIN rutas r ON r.idruta = v.ruta_id
			INNER JOIN productos p ON p.idproducto = dv.producto_id
			WHERE v.fecha >= '$fechaInicial'
			AND v.fecha <='$fechaFinal'
			AND cd.zona_id='$zonaId' ";

		if ($clienteDescuentoId != 0) {
			$sqlQuery .= " AND vcd.cliente_descuento_id = '$clienteDescuentoId' ";
		}
		if ($rutaId != 0) {
			$sqlQuery .= " AND v.ruta_id = '$rutaId' ";
		}
		if ($productoId != 0) {
			$sqlQuery .= " AND dv.producto_id = '$productoId' ";
		}
		$sqlQuery .= " ORDER BY v.fecha DESC";
		
		$sql = $this->base_datos->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}

	function listaVentaId($ventaId)
	{
		$sqlQuery = "SELECT vcd.cliente_descuento_id AS cliente_id,cd.nombre AS cliente_nombre,
			d.cantidad AS descuento_cantidad,
			vcd.descuento_id,
			vcd.cantidad,vcd.total
			FROM $this->tablaVentas vcd 
			INNER JOIN $this->tabla cd ON cd.idclientedescuento = vcd.cliente_descuento_id
			INNER JOIN descuentos d ON d.iddescuento = vcd.descuento_id
			INNER JOIN detalles_venta dv ON dv.iddetalleventa = vcd.detalle_venta_id
			INNER JOIN ventas v ON v.idventa = dv.venta_id
			INNER JOIN rutas r ON r.idruta = v.ruta_id
			WHERE v.idventa = '$ventaId' 
			ORDER BY cd.idclientedescuento DESC";
		$sql = $this->base_datos->query($sqlQuery)->fetchAll(PDO::FETCH_ASSOC);
		return $sql;
	}
}
