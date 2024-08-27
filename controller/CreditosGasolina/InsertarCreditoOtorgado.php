<?php
require('../../view/session1.php');
include('../../model/ModelCredito.php');
$modelCredito = new ModelCredito();

//Obtenemos los datos
$fecha = $_POST["formfecha"];
$idcliente = $_POST["idcliente"];
$nombre = $_POST["name"];
$domicilio = $_POST["dom"];
$colonia = $_POST["col"];
$notafac = $_POST["nota"];
$foliofisc = $_POST["folfis"];
$precio = $_POST["pre"];
$litros = $_POST["lit"];
$importe = $_POST["imp"];
$vencimiento = $_POST["formatofecha"];
$disponible = $_POST["disp"];
$usado = $_POST["used"];
$vendedor = $_POST["salesman"];
if ($_SESSION['tipoUsuario'] == "su") $zona = $_POST["zone"];
else $zona = $_SESSION["zona"];
$dia = $_POST["day"];
$mes = $_POST["month"];
$anio = $_POST["year"];
/***************************/
$tipo = $_POST["tipoProducto"];
$ieps = $_POST["ieps"];
$iva = $_POST["iva"];
$venta_full = $_POST["ventatotal"];
$ivaimpsinieps = $_POST["ivaimpsinieps"];
$impsinieps = $_POST["impsinieps"];

if ($_SESSION['tipoUsuario'] == "su") {
	if ($tipo == "aceite") $id_bomba = 0;
	else $id_bomba = $_POST["id_bomba"];
} else $id_bomba = 0;
$aceite = (!empty($_POST["aceite"])) ? $_POST["aceite"] : 1;
//subtotal
//Calculo de nuevos creditos
$nuevo_credito = $disponible - $importe;
$new_used = $usado + $importe;

//Verificamos que la nota no exista
$validarnota = $modelCredito->verificarnotagasolina($notafac, $zona);

if (!empty($validarnota)) {
	echo "<script> 
				alert('La nota o factura ya existe');
				window.location.href = '../../view/index.php?action=creditosgasolina/index.php';
			  </script>";
} else {
	if ($nuevo_credito < 0) {
		echo "<script> 
				alert('No se pudo otorgar el crédito ya que el cliente no cuenta con ese importe');
				window.location.href = '../../view/index.php?action=creditosgasolina/index.php';
			  </script>";
	} else {
		//Validamos los no nulos
		if (strlen($fecha) < 1) {
			echo "<script> 
						alert('Falta la fecha del cliente');
						window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
					  </script>";
		} else {
			if (strlen($nombre) < 1) {
				echo "<script> 
						alert('Falta el nombre del cliente')
						window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
					  </script>";
			} else {
				if (strlen($domicilio) < 1) {
					echo "<script> 
								alert('Falta el domicilio');
								window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
							  </script>";
				} else {
					if (strlen($colonia) < 1) {
						echo "<script> 
									alert('Falta la colonia');
									window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
								  </script>";
					} else {
						if (strlen($notafac) < 1) {
							echo "<script> 
									alert('Falta el número de nota o factura');
									window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
								  </script>";
						} else {
							if (strlen($precio) < 1) {
								echo "<script> 
									alert('Falta el precio');
									window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
								  </script>";
							} else {
								if (strlen($litros) < 1) {
									echo "<script> 
												alert('Faltan los litros');
												window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
											  </script>";
								} else {
									if (strlen($importe) < 1) {
										echo "<script> 
												alert('Falta el importe');
												window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
											  </script>";
									} else {
										if (strlen($vencimiento) < 1) {
											echo "<script> 
												alert('Falta la fecha de vencimiento');
												window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
											  </script>";
										} else {
											if (strlen($zona) < 1) {
												echo "<script> 
												alert('Falta la zona');
												window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
											  </script>";
											} else {
												if (strlen($foliofisc) < 1) {
													echo "<script> 
																alert('Falta el folio fiscal');
																window.location.href = '../../view/index.php?action=creditosgasolina/capturar_otorgado_cliente.php&cliente=" . $idcliente . "';
															  </script>";
												} else {
													$nuevoCredito = $modelCredito->addcreditgasolina($idcliente, $fecha, $nombre, $domicilio, $colonia, $notafac, $foliofisc, $precio, $litros, $importe, $vencimiento, $vendedor, $zona, $dia, $mes, $anio, $tipo, $ieps, $iva, $venta_full, $ivaimpsinieps, $impsinieps, $id_bomba, $aceite);
													$modelCredito->updatecredit($nuevo_credito, $idcliente, $new_used);
													echo "<script> 
																	alert('Crédito registrado');
																	window.location.href = '../../view/index.php?action=creditosgasolina/index.php'; 
																</script>";
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
}
