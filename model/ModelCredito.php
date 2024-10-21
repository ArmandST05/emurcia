<?php
include_once('Medoo.php');
use Medoo\Medoo;
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelCredito{
	
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado; //Variable para traer resultados de una consulta a la BD

	function __construct() { //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function verificarnota($nota,$zonaId,$foliofisc){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["num_factura[=]" => $nota , "zona_id" => $zonaId]]);
		return $resultado;
	}

	function obtenerCreditoGasZonaFactura($zonaId,$factura){
		$sql = $this->base_datos->select("creditos_gas","*",["AND" => ["num_factura[=]" => $factura , "zona_id" => $zonaId]]);
		return $sql;
	}
	
	function obtenerCreditoGasolinaZonaFactura($zonaId,$factura){
	    $resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["num_factura[=]" => $factura , "zona_id" => $zonaId]]);
		return $resultado;
	}

	function verificarnota_rec($nota,$zonaId){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["num_factura[=]" => $nota , "zona_id" => $zonaId]]);
		return $resultado;
	}

	function verificarnotagasolina_oto($nota,$zonaId,$foliofisc){
	    $resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["num_factura[=]" => $nota , "zona_id" => $zonaId]]);
		return $resultado;
	}

	function obtenerCreditoGasId($id){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["idcreditogas[=]" => $id]]);
		return $resultado;
	}

	function obtenerCreditoGasolinaId($id){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["idcreditogasolina[=]" => $id]]);
		return $resultado;
	}

	function verificarnotagasolina($nota,$zonaId){
	    $resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["num_factura[=]" => $nota , "zona_id" => $zonaId]]);
		return $resultado;
	}

	//====================== Credito otorgado =============================
	function addcredit($idcliente, $fecha, $nombre, $domicilio, $colonia, $notafac, $foliofisc, $precio, $litros, $importe, $vencimiento, $vendedor, $zonaId, $dia, $mes, $anio, $descuento, $comprobanteCreditoGas) {
		// Insertar los datos en la base de datos
		$this->base_datos->insert("creditos_gas", [
			"fecha" => $fecha,
			"id_cliente" => $idcliente,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"folio_fiscal" => $foliofisc,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"descuento" => $descuento,
			"fecha_vencimiento" => $vencimiento,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"tipo" => "0",
			"status" => "0",
			"importe_pagado" => "0",
			"comprobante_credito_gas" => $comprobanteCreditoGas // Guardar la ruta del comprobante
		]);
	
		return $this->base_datos->id();
	}
	
	function addcreditgasolina($idcliente,$fecha,$nombre,$domicilio,$colonia,$notafac,$foliofisc,$precio,$litros,$importe,$vencimiento,$vendedor,$zonaId,$dia,$mes,$anio,$tipo,$ieps,$iva,$venta_full,$ivaimpsinieps,$impsinieps,$id_bomba,$aceite){
		$this->base_datos->insert("creditos_gasolina",[
			"fecha" => $fecha,
			"id_cliente" => $idcliente,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"folio_fiscal" => $foliofisc,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"tipo" => "0",
			"status" => "0",
			"importe_pagado" => "0",
			"tipo_producto" => $tipo,
			"ieps" => $ieps,
			"IVA" => $iva,
			"venta_total" => $venta_full,
			"ivaimpsinieps" => $ivaimpsinieps,
			"subtotal" => $impsinieps,
			"num_bomba" => $id_bomba,
			"tipo_aceite" => $aceite
			]);
		return $this->base_datos->id();
	}

	function updatecredit($nuevoimporte,$idcliente,$nuevousado){
		$this->base_datos->update("clientes_credito",["credit_actual" => $nuevoimporte, "credit_use" => $nuevousado],["num_cliente[=]" => $idcliente]);
	}

	function actualizarCreditoCliente($nuevoimporte,$idcliente,$nuevousado){
		$this->base_datos->update("clientes_credito",["credit_actual" => $nuevoimporte, "credit_use" => $nuevousado],["num_cliente[=]" => $idcliente]);
	}

	//========================= Credito recuperado =================================
	function addcreditrec($idclien,$fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$vendedor,$zonaId,$dia,$mes,$anio){
		$this->base_datos->insert("creditos_gas",[
			"fecha" => $fecha,
			"id_cliente" => $idclien,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"importe_pagado" => $importe,
			"tipo" => "1",
			"status" => "1"
			]);
		return $this->base_datos->id();
	}
	function addcreditrecgasolina($idclien,$fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$vendedor,$zonaId,$dia,$mes,$anio){
		$this->base_datos->insert("creditos_gasolina",[
			"fecha" => $fecha,
			"id_cliente" => $idclien,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"importe_pagado" => $importe,
			"tipo" => "1",
			"status" => "1"
			]);
		return $this->base_datos->id();
	}

	function updatecreditrec($nuevoimporte,$idcliente,$nuevousado){
		$this->base_datos->update("clientes_credito",["credit_actual" => $nuevoimporte, "credit_use" => $nuevousado],["num_cliente[=]" => $idcliente]);
	}

	function obtenerCreditosGasOtorgadosCliente($tipoBusqueda,$valor){
		if($tipoBusqueda == "cliente"){
			$sql= $this->base_datos->query("SELECT * FROM creditos_gas WHERE id_cliente='$valor' AND `status`='0' AND `tipo`='0'")->fetchAll();
		}else{
			$sql= $this->base_datos->query("SELECT * FROM creditos_gas WHERE num_factura like '$valor' AND `status`='0' AND `tipo`='0'")->fetchAll();
		}
		return $sql;
	}

	function obtenerCreditosGasolinaOtorgadosCliente($tipoBusqueda,$valor){
		if($tipoBusqueda == "cliente"){
		    $sql= $this->base_datos->query("SELECT * FROM creditos_gasolina WHERE id_cliente='$valor' AND `status`='0' AND `tipo`='0'")->fetchAll();
		}else{
        	$sql= $this->base_datos->query("SELECT * FROM creditos_gasolina WHERE num_factura like '%$valor' AND `status`='0' AND `tipo`='0'")->fetchAll();
		}
		return $sql;
	}

	function updatestatus($idcliente,$numfac){
		$this->base_datos->update("creditos_gas",["status" => "1"],["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $numfac]]);
	}

	function updatestatusgasolina($idcliente,$numfac){
		$this->base_datos->update("creditos_gasolina",["status" => "1"],["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $numfac]]);
	}

	function addabono($importe,$idcliente,$numfac){
		$this->base_datos->update("creditos_gas",["importe_pagado" => $importe],["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $numfac]]);
	}

	function addabonogasolina($importe,$idcliente,$numfac){
		$this->base_datos->update("creditos_gasolina",["importe_pagado" => $importe],["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $numfac]]);
	}

	function seleccionarfactura($idcliente,$idfactura){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $idfactura, "status" => "0"]]);
		return $resultado;
	}
	function seleccionarfacturagasolina($idcliente,$idfactura){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["id_cliente[=]" => $idcliente , "num_factura" => $idfactura, "status" => "0"]]);
		return $resultado;
	}

	function seleccionarcredito_clientes($id){
	   $resultado = $this->base_datos->select("creditos_gas","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function obtenerCreditosGasCliente($id){
		$resultado = $this->base_datos->select("creditos_gas","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function obtenerCreditosGasFactura($factura){
		$resultado = $this->base_datos->select("creditos_gas","*",["num_factura[=]" => $factura]);
		return $resultado;
	}

	function seleccionarcredito_clientesgasolina($id){
	   $resultado = $this->base_datos->select("creditos_gasolina","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function obtenerCreditosGasolinaCliente($id){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function obtenerCreditosGasolinaFactura($factura){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["num_factura[=]" => $factura]);
		return $resultado;
	}

	function mod_abono($id,$fecha,$nombre,$notafac,$importe,$idabono,$zonaId,$dia,$mes,$anio,$idcliente){
		$this->base_datos->update("abonos",[
			"cliente" => $idcliente,
			"nombre" => $nombre,
			"nota" => $notafac,
			"cantidad" => $importe,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"fecha" => $fecha
			],["idabono[=]" => $id]);
	}

	function updaterectotal($id,$fecha,$nombre,$notafac,$importe,$idabono,$zonaId,$dia,$mes,$anio,$idcliente,$idrectotal){
		$this->base_datos->update("creditos_gas",[
			"fecha" => $fecha,
			"nombre" => $nombre,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio
			],["idcreditogas[=]" => $idrectotal]);
	} 
		function updaterectotalgasolina($id,$fecha,$nombre,$notafac,$importe,$idabono,$zonaId,$dia,$mes,$anio,$idcliente,$idrectotal){
		$this->base_datos->update("creditos_gas",[
			"fecha" => $fecha,
			"nombre" => $nombre,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio
			],["idcreditogas[=]" => $idrectotal]);
	} 

	function updatecredito($fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$id,$vendedor,$zonaId,$dia,$mes,$anio,$tipo_cre){
		$this->base_datos->update("creditos_gas",[
			"fecha" => $fecha,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"tipo" => $tipo_cre,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio

			],["idcreditogas[=]" => $id]);
	}

	function updatecreditogasolina($fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$id,$vendedor,$zonaId,$dia,$mes,$anio,$tipo_credito){
		$this->base_datos->update("creditos_gasolina",[
			"fecha" => $fecha,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"tipo" => $tipo_credito,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio

			],["idcreditogasolina[=]" => $id]);
	}

	function actualizarCreditoGasolina($fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$id,$vendedor,$zonaId,$dia,$mes,$anio,$tipo_credito){
		$this->base_datos->update("creditos_gasolina",[
			"fecha" => $fecha,
			"nombre" => $nombre,
			"domicilio" => $domicilio,
			"colonia" => $colonia,
			"num_factura" => $notafac,
			"precio_litro" => $precio,
			"litros" => $litros,
			"importe" => $importe,
			"fecha_vencimiento" => $vencimiento,
			"tipo" => $tipo_credito,
			"vendedor" => $vendedor,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio

			],["idcreditogasolina[=]" => $id]);
	}

	function verificarId($id){
		$resultado = $this->base_datos->select("clientes_credito","*",["num_cliente[=]" => $id]);
		return $resultado;
	}

	function traercreditoscliente($id){
		$resultado = $this->base_datos->select("creditos_gas","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function traercreditosclientegasolina($id){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["id_cliente[=]" => $id]);
		return $resultado;
	}

	function obtenerCreditosOtorgadosGasolinaCliente($id){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["id_cliente[=]" => $id , "tipo[=]" => 0]]);
		return $resultado;
	}
	
	function obtenerCreditosRecuperadosGasolinaCliente($id){
		$resultado = $this->base_datos->select("creditos_gasolina","*",["AND" => ["id_cliente[=]" => $id , "tipo[=]" => 1]]);
		return $resultado;
	}

	function obtenerCreditosOtorgadosGasCliente($id){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["id_cliente[=]" => $id , "tipo[=]" => 0]]);
		return $resultado;
	}

	function obtenerCreditosRecuperadosGasCliente($id){
		$resultado = $this->base_datos->select("creditos_gas","*",["AND" => ["id_cliente[=]" => $id , "tipo[=]" => 1]]);
		return $resultado;
	}

	function obtenerreportesabonos_cli($id_cliente){
        $sql= $this->base_datos->query("SELECT * FROM abonos where cliente='".$id_cliente."' And `status`='0'")->fetchAll();
        return $sql;
	}


	//============================ Eliminar ===========================
	function delcred($idcred,$idclien){
		$this->base_datos->delete("creditos_gas",["AND" => ["idcreditogas[=]" => $idcred , "id_cliente[=]" => $idclien]]);
	}
	
	function delcredgasolina($idcred,$idclien){
		$this->base_datos->delete("creditos_gasolina",["AND" => ["idcreditogasolina[=]" => $idcred , "id_cliente[=]" => $idclien]]);
	}

	function actualizarotorgado($id_cliente,$factura,$zonaId){
		$this->base_datos->update("creditos_gas"
			,["importe_pagado" => 0 , "status" => 0] //Campos a actualizar
			,["AND" => ["id_cliente[=]" => $id_cliente , "num_factura[=]" => $factura, "zona_id[=]" => $zonaId,"tipo[=]" => '0']]); //Where
	}

	function actualizarotorgadogasolina($id_cliente,$factura,$zonaId){
		$this->base_datos->update("creditos_gasolina"
			,["importe_pagado" => 0 , "status" => 0] //Campos a actualizar
			,["AND" => ["id_cliente[=]" => $id_cliente , "num_factura[=]" => $factura, "zona_id[=]" => $zonaId,"tipo[=]" => '0']]); //Where
	}

	function eliminarrecuperado($id_cliente,$factura,$zonaId){
		$this->base_datos->delete("creditos_gas",["AND" => ["num_factura[=]" => $factura , "id_cliente[=]" => $id_cliente, "tipo[=]" => 1 , "zona_id" => $zonaId]]);
	}
	
	function eliminarrecuperadogasolinna($id_cliente,$factura,$zonaId){
		$this->base_datos->delete("creditos_gasolina",["AND" => ["num_factura[=]" => $factura , "id_cliente[=]" => $id_cliente, "tipo[=]" => 1 , "zona_id" => $zonaId]]);
	}

	function eliminarAbonosRecuperado($id_cliente,$factura,$zonaId){
		$this->base_datos->delete("abonos",["AND" => ["cliente[=]" => $id_cliente , "nota[=]" => $factura , "zona_id[=]" => $zonaId]]);
	}

	function obtenerabonosfactura($idcliente,$num_fact,$zonaId){
		$suma = $this->base_datos->sum("abonos","cantidad",["AND" => ["cliente[=]" => $idcliente , "nota[=]" => $num_fact ,"zona_id[=]" => $zonaId]]);
		return $suma;
	}

	//========================== Abonos ===============================
	function insertarAbono($idcliente,$nombre,$notafac,$importepagado,$zonaId,$dia,$mes,$anio,$fecha){
		$this->base_datos->insert("abonos",[
			"cliente" => $idcliente,
			"nombre"  => $nombre,
			"nota" => $notafac,
			"cantidad" => $importepagado,
			"zona_id" => $zonaId,
			"dia" => $dia,
			"mes" => $mes,
			"anio" => $anio,
			"fecha" => $fecha
			]);
		return $this->base_datos->id();
	}

	function actualizarAbono($idcliente,$nota,$zonaId){
		$this->base_datos->update("abonos"
			,["status" => "1"] //Si el abono tiene un status "1" es porque ya existe el registro de recuperado
							   //En caso contrario si es "0" es porque aun no esta 100% saldado 
			,["AND" => ["cliente[=]" => $idcliente , "nota[=]" => $nota, "zona_id[=]" => $zonaId]]);
	}
}