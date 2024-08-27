<?php
include_once('Medoo.php');
use Medoo\Medoo;
require_once __DIR__ . '/../vendor/autoload.php';
/*Sintaxis de la Base de Datos
- Select : $this->base_datos->select("table" , "campos" , "where" ["campo" [restriccion] => "valor"]); Where opcional
- Insert : $this->base_datos->insert("table" , ["campo1" => "valor1", "campo2" => "valor2"]); 
- Delete : $this->base_datos->delete("table" , ["campo[condicion]" => "valor"]);
- Update : $this->base_datos->update("table" , ["campo1" => "valor1", "campo2" => "valor2"], ["campo[condicion]" => "valor"]);*/

class ModelReporte
{
	var $base_datos; //Variable para hacer la conexion a la base de datos
	var $resultado_datos; //Variable para traer resultados de una consulta a la BD
	var $suma; //Suma total de los distintos creditos

	function __construct()
	{ //Constructor de la conexion a la BD
		$this->base_datos = new Medoo();
	}

	function obtenercreditosotorgadosmespasado($zona)
	{
		$mes = date("m");
		$anio = date("Y");

		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", ["AND" => ["fecha[<=]" => $fechafinmesant, "tipo" => "0", "status" => "0", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenerid_clientecomercial($id)
	{
		$resultado_datos = $this->base_datos->query("select * from clientes_credito where num_cliente=$id")->fetchAll();
		return $resultado_datos;
	}

	function obtenersumaotorgados($zona)
	{
		$mes = date("m");
		$anio = date("Y");

		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}

		$suma = $this->base_datos->sum("creditos_gas", "importe", ["AND" => ["fecha[<=]" => $fechafinmesant, "tipo" => "0", "status[=]" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}
	function obtenersumaotorgadosgasolina($zona)
	{
		$mes = date("m");
		$anio = date("Y");

		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}

		$suma = $this->base_datos->sum("creditos_gasolina", "importe", ["AND" => ["fecha[<=]" => $fechafinmesant, "tipo" => "0", "status[=]" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperados($zona)
	{
		$mes = date("m");
		$anio = date("Y");

		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}
		$suma = $this->base_datos->sum("creditos_gas", "importe", ["AND" => ["fecha[<=]" => $fechafinmesant, "tipo" => "1", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumaabonosmespasado($zona)
	{
		$mes = date("m");
		$anio = date("Y");

		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}
		$suma = $this->base_datos->sum("abonos", "cantidad", ["AND" => ["fecha[<=]" => $fechafinmesant, "status" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	//=============================== Consulta por dia ===========================
	function obtenercreditosotorgadosdiaespecifico($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		return $resultado_datos;
	}

	function obtenercreditosotorgadosdiaespecificogasolina($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "magna"]]);
		return $resultado_datos;
	}

	function obtenercreditosotorgadosdiaespecificogasolina_pre($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "premium"]]);
		return $resultado_datos;
	}

	function obtenercreditosotorgadosdiaespecificogasolina_die($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "diesel"]]);
		return $resultado_datos;
	}

	function obtenercreditosotorgadosdiaespecificogasolina_acei($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "aceite"]]);
		return $resultado_datos;
	}

	function obtenercreditosrecuperadosdiaespecifico($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		return $resultado_datos;
	}

	function obtenercreditosrecuperadosdiaespecificogasolina($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		return $resultado_datos;
	}

	function obtenersumaotorgadosdiaespecifico($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		return $suma;
	}

	function obtenersumaotorgadosdiaespecificogasolina($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperadosdiaespecifico($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperadosdiaespecificogasolina($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		return $suma;
	}

	function obtenersumaabonos($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona]]);
		return $suma;
	}

	function obtenercreditosabonados($dia, $mes, $anio, $zona)
	{
		$resultado_datos = $this->base_datos->query("select * from abonos,clientes_credito 
		where dia=$dia and mes=$mes and anio=$anio and abonos.zona_id='$zona' 
		and abonos.cliente=clientes_credito.num_cliente")->fetchAll();
		return $resultado_datos;
	}

	//======================================= Consulta fin mes ================================
	function obtenercreditosotorgadosfinmes($zona)
	{
		$hoy = date("Y-m-d");

		$resultado_datos = $this->base_datos->query("select * from creditos_gas 
		where fecha<='$hoy' and tipo=0 and status=0 and creditos_gas.zona_id ='$zona'")->fetchAll();
		return $resultado_datos;
	}

	function obtenercreditosotorgadosfinmesrecM($zona, $fe)
	{
		$hoy1 = date("Y-m-d");
		$resultado_datos = $this->base_datos->query("SELECT SUM(`cantidad`)cantidad 
		FROM `abonos` WHERE zona_id='$zona' AND fecha>='$fe' AND fecha<='$hoy1'")->fetchAll();
		return $resultado_datos;
	}
	function obtenercreditosotorgadosfinmesgasolina($zona)
	{
		$hoy = date("Y-m-d");

		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo" => "0", "status" => "0", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenercreditosrecuperadosfinmes($zona)
	{
		$hoy = date("Y-m-d");

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo" => "1", "status" => "1", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenercreditosrecuperadosfinmesgasolina($zona)
	{
		$hoy = date("Y-m-d");

		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo" => "1", "status" => "1", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenersumaotorgadosfinmes($zona)
	{
		$hoy = date("Y-m-d");

		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumaotorgadosfinmesgasolina($zona)
	{
		$hoy = date("Y-m-d");

		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperadosfinmes($zona)
	{
		$hoy = date("Y-m-d");
		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo" => "1", "status[=]" => "1", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperadosfinmesgasolina($zona)
	{
		$hoy = date("Y-m-d");
		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo" => "1", "status[=]" => "1", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumaabonosfinmes($zona)
	{
		$hoy = date("Y-m-d");
		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $hoy, "status" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	//=================================== Creditos Relacion inicial mes pasado ========================================

	function crearpdfrelacioninicial($zona, $mes, $anio, $saldofin)
	{
		$month = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$mesreport = $mes + 1;


		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}


		$filename = "RI" . $month[$mesreport] . $anio . $zona . ".pdf";

		$comercial = $this->base_datos->select("clientes_credito", "*");
		$otorgados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		$recuperados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[>=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		$sumaotor = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		$sumarec = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "status" => "0", "zona_id[=]" => $zona]]);
		$abonos = $this->base_datos->query("SELECT nota,format(sum(cantidad),2) as suma_cantidad  
		FROM abonos where fecha <='" . $fechafinmesant[$mes] . "' and zona_id = '" . $zona . "' group by nota")->fetchAll();

		$recupero = $sumaabonos + $sumarec;
		$saldo = $sumaotor - $recupero;
		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte de relación inicial al mes de ' . $month[$mesreport] . ' del ' . $anio . ' </p>
		<p>Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Saldo: </font></th><td><font size="2"> $' . $saldofin . '</font></td></tr>
		</table>

		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Nombre Comercial </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Folio fiscal </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Importe pagado </font></th>
		<th> <font size="2"> Descuento </font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';

		$cantidad_otorgada = 0;
		$cantidd_total_abono = 0;
		foreach ($otorgados as $datos) {
			if ($datos["status"] == 1) {
				$cantidad_otorgada += $datos["importe"];
				foreach ($recuperados as $datos1) {
					if ($datos["num_factura"] == $datos1["num_factura"]) {
						$html = $html . "<tr>
						<td> <font size='2'>" . $datos["fecha"] . "</font></td>
						<td> <font size='2'>" . $datos["nombre"] . "</font></td>
						<td> <font size='2'>" . $datos["colonia"] . "</font></td>";
						foreach ($comercial as $datos3) {
							if (strcmp($datos1["id_cliente"], $datos3["num_cliente"]) == 0) {
								$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
							}
						}
						$html = $html . "<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
						<td> <font size='2'>" . $datos["folio_fiscal"] . "</font></td>
						<td align='right'> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
						<td align='right'> <font size='2'>" . $datos["litros"] . "</font></td>
						<td align='right'> <font size='2'>$" . $datos["importe"] . "</font></td>
						<td align='right'> <font size='2'>";
						foreach ($abonos as $key) {
							if ($datos["num_factura"] == $key["nota"]) {
								$html = $html . $key["suma_cantidad"];
								$cantidd_total_abono += $key["suma_cantidad"];
							} else {
								//echo "0";
							}
						}
						$html = $html . "</td>
						<td align='center'> <font size='2'>" . $datos["descuento"] . "</font></td>
						<td align='center'> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
						</tr>";
					} else {
					}
				}
			} else {
				$cantidad_otorgada = $cantidad_otorgada + $datos["importe"];
				$html = $html . "<tr>
				<td> <font size='2'>" . $datos["fecha"] . "</font></td>
				<td> <font size='2'>" . $datos["nombre"] . "</font></td>
				<td> <font size='2'>" . $datos["colonia"] . "</font></td>";
				foreach ($comercial as $datos3) {
					if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
						$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
					}
				}
				$html = $html . "<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
				<td> <font size='2'>" . $datos["folio_fiscal"] . "</font></td>
				<td align='right'> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
				<td align='right'> <font size='2'>" . $datos["litros"] . "</font></td>
				<td align='right'> <font size='2'>$" . $datos["importe"] . "</font></td>
				<td align='right'> <font size='2'>";
				$cantidd_total_abono = 0;
				foreach ($abonos as $key) {
					if ($datos["num_factura"] == $key["nota"]) {
						$html = $html . $key["suma_cantidad"];
						$cantidd_total_abono += $key["suma_cantidad"];
					}
				}
				$html = $html . "</td>
				<td align='center'> <font size='2'>" . $datos["descuento"] . "</font></td>
				<td align='center'> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
				</tr>";
			}
		}

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
		$mpdf->Output("../../view/HistorialReportes/" . $filename, "F");
	}

	function crearpdfrelacioninicialgasolina($zona, $mes, $anio, $saldofin)
	{
		$month = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$mesreport = $mes + 1;


		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$comercial = $this->base_datos->select("clientes_credito", "*");
		$filename = "RI" . $month[$mesreport] . $anio . $zona . ".pdf";
		$otorgados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		$recuperados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[>=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		$sumaotor = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		$sumarec = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "status" => "0", "zona_id[=]" => $zona]]);
		$abonos = $this->base_datos->query("SELECT nota,format(sum(cantidad),2) as suma_cantidad  
		FROM abonos where fecha <='" . $fechafinmesant[$mes] . "' and zona_id = '" . $zona . "' group by nota")->fetchAll();

		$recupero = $sumaabonos + $sumarec;
		$saldo = $sumaotor - $recupero;
		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte de relación inicial al mes de ' . $month[$mesreport] . ' del ' . $anio . ' </p>
		<p>Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Saldo: </font></th><td><font size="2"> $' . $saldofin . '</font></td></tr>
		</table>

		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Nombre Comercial </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Folio fiscal </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Importe pagado </font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';
		$cantidad_otorgada = 0;
		$cantidd_total_abono = 0;
		foreach ($otorgados as $datos) {
			if ($datos["status"] == 1) {
				$cantidad_otorgada += $datos["importe"];
				foreach ($recuperados as $datos1) {
					if ($datos["num_factura"] == $datos1["num_factura"]) {
						$html = $html . "<tr>
						<td> <font size='2'>" . $datos["fecha"] . "</font></td>
						<td> <font size='2'>" . $datos["nombre"] . "</font></td>
						<td> <font size='2'>" . $datos["colonia"] . "</font></td>";
						foreach ($comercial as $datos3) {
							if (strcmp($datos1["id_cliente"], $datos3["num_cliente"]) == 0) {
								$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
							}
						}
						$html = $html . "<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
						<td> <font size='2'>" . $datos["folio_fiscal"] . "</font></td>
						<td align='right'> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
						<td align='right'> <font size='2'>" . $datos["litros"] . "</font></td>
						<td align='right'> <font size='2'>$" . $datos["importe"] . "</font></td>
						<td align='right'> <font size='2'>";
	
						foreach ($abonos as $key) {
							if ($datos["num_factura"] == $key["nota"]) {
								$html = $html . $key["suma_cantidad"];
								$cantidd_total_abono += $key["suma_cantidad"];
							}
						}
						$html = $html . "</td>
						<td align='center'> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
						</tr>";
					} else {
					}
				}
			} else {
				$cantidad_otorgada += $datos["importe"];
				$html = $html . "<tr>
				<td> <font size='2'>" . $datos["fecha"] . "</font></td>
				<td> <font size='2'>" . $datos["nombre"] . "</font></td>
				<td> <font size='2'>" . $datos["colonia"] . "</font></td>";
				foreach ($comercial as $datos3) {
					if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
						$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
					}
				}
				$html = $html . "<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
				<td> <font size='2'>" . $datos["folio_fiscal"] . "</font></td>
				<td align='right'> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
				<td align='right'> <font size='2'>" . $datos["litros"] . "</font></td>
				<td align='right'> <font size='2'>$" . $datos["importe"] . "</font></td>
				<td align='right'> <font size='2'>";
				foreach ($abonos as $key) {
					if ($datos["num_factura"] == $key["nota"]) {
						$html = $html . $key["suma_cantidad"];
						$cantidd_total_abono += $key["suma_cantidad"];
					}
				}
				$html = $html . "</td>
				<td align='center'> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
				</tr>";
			}
		}

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
		$mpdf->Output("../../view/HistorialReportes/" . $filename, "F");
	}

	//=============================== Creditos DIA ESPECIFICO =================================

	function crearpdfdiaespecifico($dia, $mes, $anio, $zona)
	{
		$month = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

		$fecha = $anio . "-" . $mes . "-" . $dia;

		$filename = "relacioncreditosdia" . $dia . $month[$mes] . $anio . ".pdf";
		$otorgados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		$recuperados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		$sumaotor =  $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		$sumarec = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona, "status" => "0"]]);
		$abonos = $this->base_datos->select("abonos", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona]]);

		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona]]);

		$comercial = $this->base_datos->select("clientes_credito", "*");
		$totalrec = $suma;
		$saldo = $sumaotor - $totalrec;

		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte del dia ' . $dia . ' de ' . $month[$mes] . ' del ' . $anio . ' </p>
		<p> Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Creditos otorgados: </font></th><td><font size="2">' . $sumaotor . '</font></td><td>|</td><th><font size="2">Creditos recuperados: </font></th><td><font size="2">' . $suma . '</font></td></tr>
		</table>
		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<br> Creditos otorgados:
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> Nom cliente </th>
		<th> Domicilio </th>
		<th> Nombre comercial </th>
		<th> Colonia </th>
		<th> Num factura </th>
		<th> Precio litro </th>
		<th> Litros </th>	 
		<th> Importe </th>
		<th> Descuento </th>
		<th> Vencimiento </th>
		</tr>';
		foreach ($otorgados as $datos) {
			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["domicilio"] . "</td>";
			foreach ($comercial as $datos3) {
				if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
					$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
				}
			}
			$html = $html . "
			<td> " . $datos["colonia"] . "</td>
			<td> " . $datos["num_factura"] . "</td>
			<td> $" . $datos["precio_litro"] . "</td>
			<td> " . $datos["litros"] . "</td>
			<td> $" . $datos["importe"] . "</td>
			<td> " . $datos["descuento"] . "</td>
			<td> " . $datos["fecha_vencimiento"] . "</td>                            
			</tr>";
		}
		$html = $html . "</table>";
		$html = $html . '<br> Creditos recuperados (incluye abonos):
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> ID cliente </th>
		<th> Nom cliente </th>
		<th> Num factura </th> 
		<th> Abono </th>
		<th> Zona </th>
		</tr>';
		foreach ($abonos as $datos) {
			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["cliente"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["nota"] . "</td>
			<td> $" . $datos["cantidad"] . "</td>
			<td> " . $datos["zona"] . "</td>                            
			</tr>";
		}
		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}
	function crearpdfdiaespecificogasolina($dia, $mes, $anio, $zona)
	{
		$month = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

		$filename = "relacioncreditosdia" . $dia . $month[$mes] . $anio . ".pdf";
		$otorgados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "magna"]]);
		$otorgados_pre = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "premium"]]);
		$otorgados_die = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "diesel"]]);
		$otorgados_acei = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona, "tipo_producto" => "aceite"]]);


		$recuperados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		$sumaotor =  $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "0", "zona_id" => $zona]]);
		$sumarec = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "tipo" => "1", "zona_id" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona, "status" => "0"]]);
		$abonos = $this->base_datos->select("abonos", "*", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona]]);

		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id" => $zona]]);
		$comercial = $this->base_datos->select("clientes_credito", "*");

		$totalrec = $sumarec + $sumaabonos;
		$saldo = $sumaotor - $totalrec;

		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte del dia ' . $dia . ' de ' . $month[$mes] . ' del ' . $anio . ' </p>
		<p> Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Creditos otorgados: </font></th><td><font size="2">' . $sumaotor . '</font></td><td>|</td><th><font size="2">Creditos recuperados: </font></th><td><font size="2">' . $suma . '</font></td></tr>
		</table>
		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<br> Creditos otorgados Magna:
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> Nom cliente </th>
		<th> Domicilio </th>
		<th> Nombre Comercial </th>
		<th> Colonia </th>
		<th> Num factura </th>
		<th> Producto </th>
		<th> Precio litro </th>
		<th> Litros </th>
		<th> IEPS </th>	
		<th> IVA </th>	
		<th> Subtotal </th>		 
		<th> Importe </th>
		<th> Vencimiento </th>
		</tr>';
		$importe = 0;
		$litros = 0;
		$ieps = 0;
		$iva = 0;
		$subtotal = 0;

		foreach ($otorgados as $datos) {
			$importe += $datos["importe"];
			$litros += $datos["litros"];
			$ieps += $datos["ieps"];
			$iva += $datos["IVA"];
			$subtotal += $datos["subtotal"];

			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["domicilio"] . "</td>
			";
			foreach ($comercial as $datos3) {
				if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
					$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
				}
			}
			$html = $html . "
			<td> " . $datos["colonia"] . "</td>
			<td> " . $datos["num_factura"] . "</td>
			<td> " . $datos["tipo_producto"] . "</td>
			<td> $" . $datos["precio_litro"] . "</td>
			<td> " . $datos["litros"] . "</td>
			<td> $" . $datos["ieps"] . "</td>
			<td> $" . $datos["IVA"] . "</td>
			<td> $" . $datos["subtotal"] . "</td>
			<td> $" . $datos["importe"] . "</td>
			<td> " . $datos["fecha_vencimiento"] . "</td>                            
			</tr>";
		}
		$html = $html . "<tr><th>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($litros, 0) . "</td><td>" . number_format($ieps, 2) . "</td><td>" . number_format($iva, 2) . "</td><td>" . number_format($subtotal, 2) . "</td><td>" . number_format($importe, 2) . "</td><td></td></tr>";

		$html = $html . "</table>";

		/* PREMIUM*/
		$html = $html . '<br> Creditos otorgados Premium:
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> Nom cliente </th>
		<th> Domicilio </th>
		<th> Nombre Comercial </th>
		<th> Colonia </th>
		<th> Num factura </th>
		<th> Producto </th>
		<th> Precio litro </th>
		<th> Litros </th>
		<th> IEPS </th>	
		<th> IVA </th>	
		<th> Subtotal </th>		 
		<th> Importe </th>
		<th> Vencimiento </th>
		</tr>';

		
		$importep = 0;
		$litrosp = 0;
		$iepsp = 0;
		$ivap = 0;
		$subtotalp = 0;

		foreach ($otorgados_pre as $datos) {
			$importep += $datos["importe"];
			$litrosp += $datos["litros"];
			$iepsp += $datos["ieps"];
			$ivap += $datos["IVA"];
			$subtotalp += $datos["subtotal"];

			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["domicilio"] . "</td>
			";
			foreach ($comercial as $datos3) {
				if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
					$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
				}
			}
			$html = $html . "
			<td> " . $datos["colonia"] . "</td>
			<td> " . $datos["num_factura"] . "</td>
			<td> " . $datos["tipo_producto"] . "</td>
			<td> $" . $datos["precio_litro"] . "</td>
			<td> " . $datos["litros"] . "</td>
			<td> $" . $datos["ieps"] . "</td>
			<td> $" . $datos["IVA"] . "</td>
			<td> $" . $datos["subtotal"] . "</td>
			<td> $" . $datos["importe"] . "</td>
			<td> " . $datos["fecha_vencimiento"] . "</td>                            
			</tr>";
		}
		$html = $html . "<tr><th>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($litrosp, 0) . "</td><td>" . number_format($iepsp, 2) . "</td><td>" . number_format($ivap, 2) . "</td><td>" . number_format($subtotalp, 2) . "</td><td>" . number_format($importep, 2) . "</td><td></td></tr>";

		$html = $html . "</table>";

		/*DIESEL*/
		$html = $html . '<br> Creditos otorgados Diesel:
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> Nom cliente </th>
		<th> Domicilio </th>
		<th> Nombre Comercial </th>
		<th> Colonia </th>
		<th> Num factura </th>
		<th> Producto </th>
		<th> Precio litro </th>
		<th> Litros </th>
		<th> IEPS </th>	
		<th> IVA </th>	
		<th> Subtotal </th>		 
		<th> Importe </th>
		<th> Vencimiento </th>
		</tr>';

		$imported = 0;
		$litrosd = 0;
		$iepsd = 0;
		$ivad = 0;
		$subtotald = 0;
		foreach ($otorgados_die as $datos) {
			$imported += $datos["importe"];
			$litrosd += $datos["litros"];
			$iepsd += $datos["ieps"];
			$ivad += $datos["IVA"];
			$subtotald += $datos["subtotal"];

			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["domicilio"] . "</td>
			";
			foreach ($comercial as $datos3) {
				if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
					$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
				}
			}
			$html = $html . "
			<td> " . $datos["colonia"] . "</td>
			<td> " . $datos["num_factura"] . "</td>
			<td> " . $datos["tipo_producto"] . "</td>
			<td> $" . $datos["precio_litro"] . "</td>
			<td> " . $datos["litros"] . "</td>
			<td> $" . $datos["ieps"] . "</td>
			<td> $" . $datos["IVA"] . "</td>
			<td> $" . $datos["subtotal"] . "</td>
			<td> $" . $datos["importe"] . "</td>
			<td> " . $datos["fecha_vencimiento"] . "</td>                            
			</tr>";
		}
		$html = $html . "<tr><th>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($litrosd, 0) . "</td><td>" . number_format($iepsd, 2) . "</td><td>" . number_format($ivad, 2) . "</td><td>" . number_format($subtotald, 2) . "</td><td>" . number_format($imported, 2) . "</td><td></td></tr>";

		$html = $html . "</table>";

		/* Aceite */
		$html = $html . '<br> Creditos otorgados Aceite: 
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> Nom cliente </th>
		<th> Domicilio </th>
		<th> Nombre Comercial </th>
		<th> Colonia </th>
		<th> Num factura </th>
		<th> Producto </th>
		<th> Precio litro </th>
		<th> Litros </th>
		<th> IEPS </th>	
		<th> IVA </th>		
		<th> Subtotal </th>	 
		<th> Importe </th>
		<th> Vencimiento </th>
		</tr>';
		$importea = 0;
		$litrosa = 0;
		$iepsa = 0;
		$ivaa = 0;
		$subtotala = 0;

		foreach ($otorgados_acei as $datos) {
			$importea += $datos["importe"];
			$litrosa += $datos["litros"];
			$iepsa += $datos["ieps"];
			$ivaa += $datos["IVA"];
			$subtotala += $datos["subtotal"];

			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["domicilio"] . "</td>
			";
			foreach ($comercial as $datos3) {
				if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
					$html = $html . "<td> " . $datos3["nombre_comercial"] . "</td>";
				}
			}
			$html = $html . "
			<td> " . $datos["colonia"] . "</td>
			<td> " . $datos["num_factura"] . "</td>
			<td> " . $datos["tipo_producto"] . "</td>
			<td> $" . $datos["precio_litro"] . "</td>
			<td> " . $datos["litros"] . "</td>
			<td> $" . $datos["ieps"] . "</td>
			<td> $" . $datos["IVA"] . "</td>
			<td> $" . $datos["subtotal"] . "</td>
			<td> $" . $datos["importe"] . "</td>
			<td> " . $datos["fecha_vencimiento"] . "</td>                            
			</tr>";
		}
		$html = $html . "<tr><th>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . number_format($litrosa, 0) . "</td><td>" . number_format($iepsa, 2) . "</td><td>" . number_format($ivaa, 2) . "</td><td>" . number_format($subtotala, 2) . "</td><td>" . number_format($importea, 2) . "</td><td></td></tr>";

		$html = $html . "</table>";

		$html = $html . '<br> Creditos recuperados (incluye abonos):
		<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> Fecha </th>
		<th> ID cliente </th>
		<th> Nom cliente </th>
		<th> Num factura </th> 
		<th> Abono </th>
		<th> Zona </th>
		</tr>';
		$cantidad = 0;
		foreach ($abonos as $datos) {
			$cantidad += $datos["cantidad"];
			$html = $html . "<tr>
			<td> " . $datos["fecha"] . "</td>
			<td> " . $datos["cliente"] . "</td>
			<td> " . $datos["nombre"] . "</td>
			<td> " . $datos["nota"] . "</td>
			<td> $" . $datos["cantidad"] . "</td>
			<td> " . $datos["zona"] . "</td>                            
			";
		}
		$html = $html . "<tr><th>Total</th><td></td><td></td><td></td><td>" . number_format($cantidad, 2) . "</td><td></td></tr>";

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	//=========================== Creditos Final Mes ===================================

	function crearpdffinmes($zona)
	{
		$month = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

		$mes = date("m");
		$anio = date("Y");
		$hoy = date("Y-m-d");

		$filename = "relacionfinalmes" . $month[$mes] . $anio . ".pdf";

		$otorgados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id" => $zona]]);
		$recuperados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "1", "status[=]" => "1", "zona_id" => $zona]]);
		$sumaotor =  $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $hoy, "status" => "0", "zona_id[=]" => $zona]]);

		$saldo = $sumaotor - $sumaabonos;
		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte final del mes de ' . $month[$mes] . ' del ' . $anio . ' </p>
		<p> Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Saldo: </font></th><td><font size="2">' . $saldo . '</font></td></tr>
		</table>
		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Domicilio </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Importe pagado</font></th>
		<th> <fint size="2">Descuento</font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';
		foreach ($otorgados as $datos) {
			$html = $html . "<tr>
			<td> <font size='2'>" . $datos["fecha"] . "</font></td>
			<td> <font size='2'>" . $datos["nombre"] . "</font></td>
			<td> <font size='2'>" . $datos["domicilio"] . "</font></td>
			<td> <font size='2'>" . $datos["colonia"] . "</font></td>
			<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
			<td> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
			<td> <font size='2'>" . $datos["litros"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe_pagado"] . "</font></td>
			<td> <font size='2'>" . $datos["descuento"] . "</font></td>
			<td> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
			</tr>";
		}
		$html = $html . "<tr><th><font size='2'>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td><font size='2'>$" . $sumaotor . "</font></td><td></td></table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}
	function crearpdffinmesgasolina($zona, $saldo =null, $otorgado = null, $abonos = null)
	{
		$month = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

		$mes = date("m");
		$anio = date("Y");
		$hoy = date("Y-m-d");

		$filename = "relacionfinalmes" . $month[$mes] . $anio . ".pdf";

		$otorgados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id" => $zona]]);
		$recuperados = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "1", "status[=]" => "1", "zona_id" => $zona]]);
		$sumaotor =  $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $hoy, "tipo[=]" => "0", "status[=]" => "0", "zona_id" => $zona]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $hoy, "status" => "0", "zona_id[=]" => $zona]]);


		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte final del mes de ' . $month[$mes] . ' del ' . $anio . ' </p>
		<p> Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Saldo: </font></th><td><font size="2">' . $saldo . '</font></td></tr>
		<tr><th><font size="2">Otorgado durante el mes: </font></th><td><font size="2">' . $otorgado . '</font></td></tr>
		<tr><th><font size="2">Recuperado: </font></th><td><font size="2">' . $abonos . '</font></td></tr>
		</table>
		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los creditos otorgados con los que cuenta actualmente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . '<table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Domicilio </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Importe pagado</font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';
		foreach ($otorgados as $datos) {
			$html = $html . "<tr>
			<td> <font size='2'>" . $datos["fecha"] . "</font></td>
			<td> <font size='2'>" . $datos["nombre"] . "</font></td>
			<td> <font size='2'>" . $datos["domicilio"] . "</font></td>
			<td> <font size='2'>" . $datos["colonia"] . "</font></td>
			<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
			<td> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
			<td> <font size='2'>" . $datos["litros"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe_pagado"] . "</font></td>
			<td> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
			</tr>";
		}
		$html = $html . "<tr><th><font size='2'>Total</th><td></td><td></td><td></td><td></td><td></td><td></td><td><font size='2'>$" . $sumaotor . "</font></td><td></td></table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	//================================ REPORTES DESCUENTOS===========================

	function obtenerdescuentosdiaespecificocreditos($dia, $mes, $anio, $zona)
	{
		$fecha = $anio . "-" . $mes . "-" . $dia;
		$resultado_datos = $this->base_datos->query("SELECT * FROM creditos_gas 
		where descuento>'0.00' AND fecha='$fecha' AND zona_id='$zona'");
		return $resultado_datos;
	}

	function obtenersumadescuentoscreditos($dia, $mes, $anio, $zona)
	{
		$fecha = $anio . "-" . $mes . "-" . $dia;
		$resultado_datos = $this->base_datos->query("SELECT FORMAT(SUM(descuento),2)descuento,
		FORMAT(SUM(importe),2)venta,FORMAT(SUM(litros),2) litros,SUM(litros) lit,SUM(descuento) des 
		FROM creditos_gas Where descuento>'0.00' AND fecha='$fecha' AND zona_id='$zona'");
		return $resultado_datos;
	}

	function obtenersumaolitros($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("descuento", "litros", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumaoimporte($dia, $mes, $anio, $zona)
	{
		$suma = $this->base_datos->sum("descuento", "importe", 
		["AND" => ["dia" => $dia, "mes" => $mes, "anio" => $anio, "zona_id[=]" => $zona]]);
		return $suma;
	}

	//=================================== New Inicial select month ===========================================

	function obtenercreditosotorgadosmespasado2($zona, $mes, $anio)
	{

		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenerid_cliente()
	{
		$resultado_datos = $this->base_datos->select("clientes_credito", "*");
		return $resultado_datos;
	}

	function obtenercreditosotorgadosmespasado2gasolina($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenercredrecmespasado2gas($zona, $mes, $anio)
	{

		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenercreditosrecuperadosmespasado2($zona, $mes, $anio)
	{

		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["fecha[>]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);

		return $resultado_datos;
	}

	function obtenercreditosrecuperadosmespasado2_clientes($cliente)
	{

		$resultado_datos = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["id_cliente[=]" => $cliente, "tipo" => "1"]]);

		return $resultado_datos;
	}
	function obtenercreditosrecuperadosmespasado2gasolina($zona, $mes, $anio)
	{

		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$resultado_datos = $this->base_datos->select("creditos_gasolina", "*", 
		["AND" => ["fecha[>]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		return $resultado_datos;
	}

	function obtenersumaotorgados2($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}
	function obtenersumaotorgados2gasolina($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "0", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumarecuperados2($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		return $suma;
	}
	function obtenersumarecuperados2gasolina($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "tipo" => "1", "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenersumaabonosmespasado2($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["fecha[<=]" => $fechafinmesant[$mes], "status[=]" => '0',  "zona_id[=]" => $zona]]);
		return $suma;
	}

	function obtenerabonosmespasado2($zona, $mes, $anio)
	{
		if ($anio == '2016' || $anio == '2020' || $anio == '2024' || $anio == '2028' || $anio == '2032' || $anio == '2036' || $anio == '2040') {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-29", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		} else {
			$fechafinmesant = ["1" => $anio . "-01-31", "2" => $anio . "-02-28", "3" => $anio . "-03-31", "4" => $anio . "-04-30", "5" => $anio . "-05-31", "6" => $anio . "-06-30", "7" => $anio . "-07-31", "8" => $anio . "-08-31", "9" => $anio . "-09-30", "10" => $anio . "-10-31", "11" => $anio . "-11-30", "12" => $anio . "-12-31"];
		}

		$suma = $this->base_datos->query("SELECT nota,format(sum(cantidad),2) as suma_cantidad,
		sum(cantidad) as suma_cantidad_to  
		FROM abonos where fecha <='" . $fechafinmesant[$mes] . "' and zona_id = '" . $zona . "' group by nota")->fetchAll();
		return $suma;
	}

	//******************************Reporte Saldos de Clientes ********************/
	function obtenerreportesstorgados($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM creditos_gas 
		where id_cliente='" . $id_cliente . "' And tipo='0' ORDER BY fecha DESC")->fetchAll();
		return $sql;
	}

	function obtenersaldozonastorgados($zona)
	{
		$sql = $this->base_datos->query("SELECT (SUM(importe)-(SUM(importe_pagado)))total 
		FROM `creditos_gasolina` WHERE `tipo`='0' AND zona_id='$zona' AND status='0'")->fetchAll();
		return $sql;
	}
	
	function obtenersaldozonastorgados2($zona)
	{
		$sql = $this->base_datos->query("SELECT (SUM(importe)-(SUM(importe_pagado)))total_chido 
		FROM `creditos_gas` WHERE `tipo`='0' AND zona_id='$zona' AND status='0'")->fetchAll();
		return $sql;
	}

	function obtenerreportesstorgadosgasolina($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM creditos_gasolina 
		where id_cliente='" . $id_cliente . "' And tipo='0'")->fetchAll();
		return $sql;
	}

	function obtener_folio_fiscal($zona)
	{
		$sql = $this->base_datos->query("SELECT folio_fiscal,num_factura FROM creditos_gas 
		WHERE zona_id='$zona' AND tipo='0'")->fetchAll();
		return $sql;
	}

	function obtenerreportesrecuperados($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM creditos_gas 
		where id_cliente='" . $id_cliente . "' And tipo='1'")->fetchAll();
		return $sql;
	}

	function obtenerreportesrecuperadosgasolina($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM creditos_gasolina 
		where id_cliente='" . $id_cliente . "' And tipo='1'")->fetchAll();
		return $sql;
	}

	function obtenerreportesabonos_cli($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM abonos 
		where cliente='" . $id_cliente . "' And `status`='0'")->fetchAll();
		return $sql;
	}

	function suma_otorgados($id_cliente)
	{

		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => '0']]);
		return $suma;
	}
	
	function suma_otorgadosgasolina($id_cliente)
	{
		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => '0']]);
		return $suma;
	}

	function suma_recuperados($id_cliente)
	{
		$suma = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => '1']]);
		return $suma;
	}

	function suma_recuperadosgasolina($id_cliente)
	{
		$suma = $this->base_datos->sum("creditos_gasolina", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => '1']]);
		return $suma;
	}

	function totalAbonosCliente($id_cliente)
	{
		$suma = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["cliente[=]" => $id_cliente, "status[=]" => '0']]);
		return $suma;
	}

	function crearPdfGastosAdministrativosZonaFechas($zonaId,$zona,$mesInicial,$anioInicial,$mesFinal,$anioFinal)
	{
		include_once('ModelGasto.php');
		$modelGasto = new ModelGasto();

		$meses = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$filename = "GastosAdministrativos-" . $meses[$mes] . $anio . $zona . ".pdf";

		$gastos = $modelGasto->obtenerGastosAdministrativosZonaEntreFechas($zonaId,$mesInicial,$anioInicial,$mesFinal,$anioFinal);
		$totalGastos = 0;

		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		.title {
			border: 0px;
			border-collapse: collapse;
		}
		</style>
		  <div style="text-align: left; font-weight: bold;">
		<table class="title" style="width:100%"> 
			<tr class="title">
				<td class="title"><img src="../../images/emurcia.png" width="150" /></td>
				<td class="title" style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
				<p>Reporte de Gastos Administrativos ' . '' . '</p>
				<p>Zona: ' . $zona . '</td>
			</tr>
		</table>
		<br>
		<table class="title">
			<tr class="title"><td class="title"><h3>Detalle de Gastos Administrativos</h3></td></tr>
		</table>
		<br>
		</div>';

		$html = $html . '<table style="width:100%; border: 1px solid black; border-collapse: collapse;" autosize="2">
		<tr>
			<th>Zona </th>
			<th>Origen </th>
			<th>Concepto </th>
			<th>Año </th>
			<th>Mes</th>
			<th>Cantidad </th>
			<th>Observaciones </th>
		</tr>';
		foreach ($gastos as $gasto) {
			$totalGastos += $gasto["cantidad"];
			$html = $html . "
			<tr>
				<td>" . $gasto["zona"] . "</td>
				<td>" . $gasto["origen_gasto"] . "</td>
				<td>" . $gasto["concepto_gasto"] . "</td>
				<td>" . $gasto["anio"] . "</td>
				<td>" . $meses[$gasto["mes"]] . "</td>
				<td>$" . number_format($gasto["cantidad"], 2) . "</td>
				<td>" . $gasto["observaciones"] . "</td>                         
			</tr>";
		}

		$html = $html . "
		<tr>
			<td><b>Total</b></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>$" . number_format($totalGastos, 2) . "</b></td>
			<td></td>                           
		</tr>";

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	function crearPdfGastosRutasZonaFechas($zonaId,$zona,$mesInicial,$anioInicial,$mesFinal,$anioFinal)
	{
		include_once('ModelGasto.php');
		$modelGasto = new ModelGasto();

		$meses = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$filename = "GastosAlmacen-" . $meses[$mes] . $anio . $zona . ".pdf";

		$gastos = $modelGasto->obtenerGastosRutasZonaEntreFechas($zonaId,$mesInicial,$anioInicial,$mesFinal,$anioFinal);
		$totalGastos = 0;

		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		.title {
			border: 0px;
			border-collapse: collapse;
		}
		</style>
		  <div style="text-align: left; font-weight: bold;">
		<table class="title" style="width:100%"> 
			<tr class="title">
				<td class="title"><img src="../../images/emurcia.png" width="150" /></td>
				<td class="title" style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
				<p>Reporte de Gastos Almacén ' . '' . '</p>
				<p>Zona: ' . $zona . '</td>
			</tr>
		</table>
		<br>
		<table class="title">
			<tr class="title"><td class="title"><h3>Detalle de Gastos Almacén</h3></td></tr>
		</table>
		<br>
		</div>';

		$html = $html . '<table style="width:100%; border: 1px solid black; border-collapse: collapse;" autosize="2">
		<tr>
			<th>Zona </th>
			<th>Concepto </th>
			<th>Año </th>
			<th>Mes</th>
			<th>Cantidad </th>
			<th>Observaciones </th>
		</tr>';
		foreach ($gastos as $gasto) {
			$totalGastos += $gasto["cantidad"];
			$html = $html . "
			<tr>
				<td>" . $gasto["zona"] . "</td>
				<td>" . $gasto["concepto_gasto"] . "</td>
				<td>" . $gasto["anio"] . "</td>
				<td>" . $meses[$gasto["mes"]] . "</td>
				<td>$" . number_format($gasto["cantidad"], 2) . "</td>
				<td>" . $gasto["observaciones"] . "</td>                         
			</tr>";
		}

		$html = $html . "
		<tr>
			<td><b>Total</b></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>$" . number_format($totalGastos, 2) . "</b></td>
			<td></td>                           
		</tr>";

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	function crearPdfInventarioZonaFecha($zonaId,$zona,$fecha)
	{
		include('ModelRuta.php');
		include('ModelInventario.php');
		$modelRuta = new ModelRuta();
		$modelInventario = new ModelInventario();

		$meses = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$filename = "Inventario-" . $fecha ."-". $zona . ".pdf";

		$rutas = $modelRuta->inventarioRutas($zonaId);
		$totalKilos = 0;
		$totalLitros = 0;

		$mpdf = new \Mpdf\Mpdf();

		$html = '
		<style>
		table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
		}
		.title {
			border: 0px;
			border-collapse: collapse;
		}
		</style>
		  <div style="text-align: left; font-weight: bold;">
		<table class="title" style="width:100%"> 
			<tr class="title">
				<td class="title"><img src="../../images/logo.png" width="150" /></td>
				<td class="title" style="text-align: center; font-weight: bold;">
				<p>Reporte de Inventario ' . '' . '</p>
				<p>Zona: ' . $zona . '</td>
			</tr>
		</table>
		<br>
		<table class="title">
			<tr class="title"><td class="title"><h3>Detalle de Inventario por Ruta/Unidad</h3></td></tr>
		</table>
		<br>
		</div>';

		$html = $html . '<table style="width:100%; border: 1px solid black; border-collapse: collapse;" autosize="2">
		<tr>
			<th>Ruta/Unidad</th>
			<th>Producto</th>
			<th>Mínimo</th>
			<th>Capacidad</th>
			<th>Kilos</th>
			<th>Litros</th>
			<th>Actual</th>
			<th>Porcentaje</th>
		</tr>';
		foreach ($rutas as $ruta) {
			//Cálculo de inventario
			$productoId = $ruta["producto_id"];

			$entradasInventario = $modelInventario->obtenerEntradasRutaProductoCorteFecha($ruta["ruta_id"], $productoId,$fecha);
			$entradasInventario = reset($entradasInventario);
			$entradasInventario = $entradasInventario["cantidad"];

			$salidasInventario = $modelInventario->obtenerSalidasRutaProductoCorteFecha($ruta["ruta_id"], $productoId,$fecha);
			$salidasInventario = reset($salidasInventario);
			$salidasInventario = $salidasInventario["cantidad"];

			if ($entradasInventario > 0 && $salidasInventario > 0) {
			  $inventarioActual = $entradasInventario - $salidasInventario;
			} else {
			  $inventarioActual = $entradasInventario;
			}
			$porcentajeActual = 0;

			//Si el producto es Lts realizar cálculo de inventario en base a porcentaje actual y capacidad de Pipa
			if ($productoId == 4) {
			  $litros = (($inventarioActual * $ruta["ruta_capacidad"]) / 100);
			  $totalLitros += $litros;

			  $kilos = ($litros * .54);
			  $totalKilos += $kilos;
			} else {
			  $litros = ($inventarioActual * $ruta["producto_capacidad"]);
			  $totalLitros += $litros;

			  $kilos = ($litros * .54);
			  $totalKilos += $kilos;
			}
			//Cálculo de inventario

			$html = $html . "
			<tr>
				<td>" . $ruta["ruta_nombre"] . "</td>
				<td>" . $ruta["producto_nombre"] . "</td>
				<td>1</td>
				<td>" . $ruta["ruta_capacidad"] . "</td>
				<td>" . number_format($kilos, 2) . "</td>
				<td>" . number_format($litros, 2) . "</td>   
				<td>" . number_format($inventarioActual, 2) . "</td>  
				<td>" . number_format($porcentajeActual, 2) . "</td>                       
			</tr>";
		}

		$html = $html . "
		<tr>
			<td><b>Total</b></td>
			<td></td>
			<td></td>
			<td></td>
			<td><b>$" . number_format($totalKilos, 2) . "</b></td>
			<td><b>$" . number_format($totalLitros, 2) . "</b></td>
			<td></td>     
			<td></td>                       
		</tr>";

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	function crearPdfSaldoClientes($id_cliente, $zona, $nombre)
	{
		$month = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
		$mes = date("m");
		$anio = date("Y");

		$mesconsulta = $mes - 1;
		if ($mesconsulta == "0") {
			$mesconsulta = "12";
		}
		$filename = "RSC" . $month[$mes] . $anio . $zona . ".pdf";
		$fecha = $this->base_datos->query("select date_sub(str_to_date('" . $anio . "-" . $mes . "-" . "01','%Y-%m-%d'),interval 1 day)")->fetchAll();
		foreach ($fecha as $data) {
			$fechafinmesant = $data[0];
		}
		$otorgados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => "0"]]);
		$recuperados = $this->base_datos->select("creditos_gas", "*", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => "1"]]);
		$abonos = $this->base_datos->select("abonos", "*", 
		["AND" => ["cliente[=]" => $id_cliente, "status[=]" => "0"]]);
		$sumaotor = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => "0"]]);
		$sumarec = $this->base_datos->sum("creditos_gas", "importe", 
		["AND" => ["id_cliente[=]" => $id_cliente, "tipo[=]" => "1"]]);
		$sumaabonos = $this->base_datos->sum("abonos", "cantidad", 
		["AND" => ["cliente[=]" => $id_cliente, "status[=]" => "0"]]);

		$total_rec = $sumarec + $sumaabonos;
		$saldo_to = $sumaotor - $total_rec;

		$mpdf = new \Mpdf\Mpdf();

		$html = '<div style="text-align: left; font-weight: bold;">
		<table border="0" style="width:100%"> 
		<tr><td><img src="../../images/emurcia.png" width="150" /></td>
		<td style="text-align: center; font-weight: bold;"><p>Grupo Emurcia</p>
		<p>Reporte de Saldos del cliente ' . $nombre . '</p>
		<p>Zona: ' . $zona . '</td>
		</tr>
		</table>
		<br>
		<table border="0">
		<tr><th><font size="2">Total otorgado: </font></th><td><font size="2"> $' . $sumaotor . '</font></td></tr>
		<tr><th><font size="2">Total recuperado: </font></th><td><font size="2"> $' . $total_rec . '</font></td></tr>
		<tr><th><font size="2">Saldo: </font></th><td><font size="2"> $' . $saldo_to . '</font></td></tr>
		</table>
		<table border="0">
		<tr><td><p><font >En este PDF se muestran todos los créditos con los que cuenta este cliente:</font></p></td></tr>
		</table>
		<br>

		</div>';

		$html = $html . 'Créditos Otorgados <table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Domicilio </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';
		foreach ($otorgados as $datos) {
			$html = $html . "<tr>
			<td> <font size='2'>" . $datos["fecha"] . "</font></td>
			<td> <font size='2'>" . $datos["nombre"] . "</font></td>
			<td> <font size='2'>" . $datos["domicilio"] . "</font></td>
			<td> <font size='2'>" . $datos["colonia"] . "</font></td>
			<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
			<td> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
			<td> <font size='2'>" . $datos["litros"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe"] . "</font></td>
			<td> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
			</tr>";
		}

		$html = $html . '</table><br> Créditos Recuperados <table border="1" style="width:100%;" autosize="2">
		<tr>
		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">Nombre </font></th>
		<th> <font size="2">Domicilio </font></th>
		<th> <font size="2">Colonia </font></th>
		<th> <font size="2">Not/Fac </font></th>
		<th> <font size="2">Precio litro </font></th>
		<th> <font size="2">Litros </font></th>	 
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Vencimiento </font></th>
		</tr>';
		foreach ($recuperados as $datos) {
			$html = $html . "<tr>
			<td> <font size='2'>" . $datos["fecha"] . "</font></td>
			<td> <font size='2'>" . $datos["nombre"] . "</font></td>
			<td> <font size='2'>" . $datos["domicilio"] . "</font></td>
			<td> <font size='2'>" . $datos["colonia"] . "</font></td>
			<td> <font size='2'>" . $datos["num_factura"] . "</font></td>
			<td> <font size='2'>$" . $datos["precio_litro"] . "</font></td>
			<td> <font size='2'>" . $datos["litros"] . "</font></td>
			<td> <font size='2'>$" . $datos["importe"] . "</font></td>
			<td> <font size='2'>" . $datos["fecha_vencimiento"] . "</font></td>                            
			</tr>";
		}
		$html = $html . '</table><br> Abonos <table border="1" style="width:100%;" autosize="2">
		<tr>

		<th> <font size="2">Fecha </font></th>
		<th> <font size="2">ID cliente </font></th>
		<th> <font size="2">Nombre cliente </font></th>
		<th> <font size="2">Numero factura </font></th>
		<th> <font size="2">Importe </font></th>
		<th> <font size="2">Zona </font></th>

		</tr>';
		foreach ($abonos as $datos) {
			$html = $html . "<tr>

			<tr>
			<td><font size='2'> " . $datos["fecha"] . "</font></td>
			<td><font size='2'> " . $datos["cliente"] . "</font></td>
			<td><font size='2'> " . $datos["nombre"] . "</font></td>
			<td><font size='2'> " . $datos["nota"] . "</font></td>
			<td><font size='2'> " . $datos["cantidad"] . "</font></td>
			<td><font size='2'> " . $datos["zona"] . "</font></td>                            
			</tr>";
		}

		$html = $html . "</table>";
		$mpdf->WriteHTML($html);
		$mpdf->Output($filename, "D");
	}

	function datos_clientes($id_cliente)
	{
		$sql = $this->base_datos->query("SELECT * FROM clientes_credito 
		WHERE num_cliente='" . $id_cliente . "'")->fetchAll();
		return $sql;
	}

}
