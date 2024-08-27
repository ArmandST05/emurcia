<?php
	include('../../model/ModelCredito.php');
	$mode_credit = new ModelCredito();

	//Obtenemos los datos
	$fecha = $_GET["formfecha"];
	$idcliente = $_GET["id_cliente"];
	$nombre = $_GET["name"];
	$domicilio = $_GET["dom"];
	$colonia = $_GET["col"];
	$notafac = $_GET["nota"];
	$precio = $_GET["pre"];
	$litros = $_GET["lit"];
	$importe = $_GET["imp"];
	$vencimiento = $_GET["formatofecha"];
	$old_importe = $_GET["oldimp"];
	$disponible = $_GET["disp"];
	$zona = $_GET["zone"];
	$usado = $_GET["used"];
	$vendedor = $_GET["salesman"];
	$id = $_GET["id_credito"];
	$dia = $_GET["day"];
	$mes = $_GET["month"];
	$anio = $_GET["year"];
	$tipo_credito= $_GET["tipo_credito"];

	//Calculo de nuevos creditos
	if($importe > $old_importe){
		$error = $importe - $old_importe;
		$nuevo_disponible = $disponible - $error;
		$new_used = $usado + $error;
	}else if($importe < $old_importe){
		$error = $old_importe - $importe;
		$nuevo_disponible = $disponible + $error;
		$new_used = $usado - $error;
	}else if($importe = $old_importe){
		$error = 0;
		$nuevo_disponible = $disponible;
		$new_used = $usado;
	}
	//Verificamos que la nota no exista
	$validarnota = $mode_credit->verificarnota($notafac,$zona);

	if(empty($validarnota)){
		echo "<script> 
				alert('La nota o factura no existe');
				window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
			  </script>";
	}else{
		if($nuevo_disponible < 0){
			echo "<script> 
				alert('No se pudo otorgar el credito ya que el cliente no cuenta con ese importe');
				window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
			  </script>";
		}else{
			//Validamos los no nulos
			if(strlen($fecha)<1){
				echo "<script> 
						alert('Falta la fecha del cliente');
						window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
					  </script>";
			}else{
				if(strlen($nombre)<1){
				echo "<script> 
						alert('Falta el nombre del cliente')
						window.location.href = '../../view/clientes/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
					  </script>";
				}else{
					if(strlen($domicilio)<1){
						echo "<script> 
								alert('Falta el domicilio');
								window.location.href = '../../view/clientes/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
							  </script>";
					}else{
						if(strlen($colonia)<1){
							echo "<script> 
									alert('Falta la colonia');
									window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
								  </script>";
						}else{
							if(strlen($notafac)<1){
								echo "<script> 
									alert('Falta el numero de nota o factura');
									window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
								  </script>";
							}else{
								if(strlen($precio)<1){

									echo "<script> 
									alert('Falta el precio');
									window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
								  </script>";
								}else{
									if(strlen($litros)<1){
										echo "<script> 
												alert('Faltan los litros');
												window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
											  </script>";
									}else{
										if(strlen($importe)<1){
											echo "<script> 
												alert('Falta el importe');
												window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
											  </script>";
										}else{
											if(strlen($vencimiento)<1){
												echo "<script> 
												alert('Falta la fecha de vencimiento');
												window.location.href = '../../view/creditos/mod_info_cred.php?numfac=".$notafac."&name=".$idcliente."';
											  </script>";
											}else{
												$mode_credit->updatecredito($fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$id,$vendedor,$zona,$dia,$mes,$anio,$tipo_credito);
												$mode_credit->updatecredit($nuevo_disponible,$idcliente,$new_used);
												echo "<script> 
														alert('Credito modificado');
														window.location.href = '../../view/creditos/index_creditos.php'; 
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
					
?>

 