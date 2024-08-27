<?php
	include('../../model/ModelCredito.php');
	$modelCredito = new ModelCredito();

	//Obtenemos los datos
	$fecha = $_POST["formfecha"];
	$idcliente = $_POST["idcliente"];
	$nombre = $_POST["name"];
	$domicilio = $_POST["dom"];
	$colonia = $_POST["col"];
	$notafac = $_POST["nota"];
	$precio = $_POST["pre"];
	$litros = $_POST["lit"];
	$importe = $_POST["imp"]; //Importe capturado en el campo importe (Pagado en esta ocasión)
	$vencimiento = $_POST["formatofecha"];
	$disponible = $_POST["disp"]; //Credito disponible del cliente
	$usado = $_POST["used"]; //Credito usado del cliente
	$limite = $_POST["limit"]; //Limite del credito del cliente
	$vendedor = $_POST["salesman"];
	$zona = $_POST["zona"];
	$dia = $_POST["day"];
	$mes = $_POST["month"];
	$anio = $_POST["year"];
	$oldimpor = $_POST["oldimpor"]; //Importe original de la factura
	$importepagado = $_POST["imporact"];//importepagado = abonado + pagado

	$nuevo_credito = $disponible + $importe; //Nuevo credito disponible del usuario
	$new_used = $usado - $importe; //Nuevo credito usado del cliente
	//Verificamos que la nota no exista
	$validarNota = $modelCredito->obtenerCreditoGasolinaZonaFactura($zona,$notafac);

	if(empty($validarNota)){
		echo "<script> 
				alert('La nota o factura ingresada no existe');
				window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
			  </script>";
	}else{
		if(strlen($fecha)<1){
			echo "<script> 
						alert('Falta la fecha');
						window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
					  </script>";
		}else{
			//Validamos los no nulos window.location.href = "validalodemas.php?idcliente='.$idcliente.'&name='.$nombre.'&dom='.$domicilio.'&col='.$colonia.'&disp='.$disponible.'&used='.$usado.'&limit='.$limite.'&nota='.$notafac.'&pre='.$precio.'&lit='.$litros.'&imp='.$importe.'&formatofecha='.$vencimiento.'&formfecha='.$fecha.'";
			if(strlen($domicilio)<1){
				echo "<script> 
						alert('Falta el domicilio');
						window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
					  </script>";
			}else{
				if(strlen($colonia)<1){
					echo "<script> 
							alert('Falta la colonia');
							window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
						  </script>";
				}else{
					if(strlen($notafac)<1){
						echo "<script> 
								alert('Falta el numero de nota o factura');
								window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
							  </script>";
					}else{
						if(strlen($precio)<1){
							echo "<script> 
									alert('Falta el precio');
									window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
								  </script>";
						}else{
							if(strlen($litros)<1){
								echo "<script> 
										alert('Faltan los litros');
										window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
									  </script>";
							}else{
								if(strlen($importe)<1){
									echo "<script> 
											alert('Falta el importe');
											window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
										  </script>";
									
								}else{
									if(strlen($vencimiento)<1){
										echo "<script> 
												alert('Falta la fecha de vencimiento');
												window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
											  </script>";
									}else{
										if(strlen($nombre)<1){
											echo "<script> 
													alert('Falta el nombre del cliente')
													window.location.href = '../../view/index.php?action=creditosgasolina/capturar_recuperado_cliente.php&cliente=".$idCliente."&numFactura=".$notafac."';
					  							  </script>";
										}else{
											if(floatval($oldimpor) == floatval($importepagado)){
												$modelCredito->updatestatusgasolina($idcliente,$notafac);
												$modelCredito->addabonogasolina($importepagado,$idcliente,$notafac);
												$modelCredito->addcreditrecgasolina($idcliente,$fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importepagado,$vencimiento,$vendedor,$zona,$dia,$mes,$anio);
												$modelCredito->updatecreditrec($nuevo_credito,$idcliente,$new_used);
												$modelCredito->insertarAbono($idcliente,$nombre,$notafac,$importe,$zona,$dia,$mes,$anio,$fecha);
												$modelCredito->actualizarAbono($idcliente,$notafac,$zona);
												echo "<script> 
													alert('Crédito registrado');
													window.location.href = '../../view/index.php?action=creditosgasolina/index.php'; 
												  </script>";
											}else{
												echo "<script> 
													alert('Crédito abonado');
													window.location.href = '../../view/index.php?action=creditosgasolina/index.php';
												  </script>";
												$modelCredito->addabonogasolina($importepagado,$idcliente,$notafac);
												$modelCredito->updatecreditrec($nuevo_credito,$idcliente,$new_used);
												$modelCredito->insertarAbono($idcliente,$nombre,$notafac,$importe,$zona,$dia,$mes,$anio,$fecha);
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
?>
