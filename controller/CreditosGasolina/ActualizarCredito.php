<?php
	include('../../model/ModelCredito.php');
	$modelCredito = new ModelCredito();

	//Obtenemos los datos
	$fecha = $_POST["formfecha"];
	$idcliente = $_POST["id_cliente"];
	$nombre = $_POST["name"];
	$domicilio = $_POST["dom"];
	$colonia = $_POST["col"];
	$notafac = $_POST["nota"];
	$precio = $_POST["pre"];
	$litros = $_POST["lit"];
	$importe = $_POST["imp"];
	$vencimiento = $_POST["formatofecha"];
	$old_importe = $_POST["oldimp"];
	$disponible = $_POST["disp"];
	$zona = $_POST["zone"];
	$usado = $_POST["used"];
	$vendedor = $_POST["salesman"];
	$id = $_POST["id_credito"];
	$dia = $_POST["day"];
	$mes = $_POST["month"];
	$anio = $_POST["year"];
	$tipo_credito= $_POST["tipo_credito"];

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
	$validarnota = $modelCredito->verificarnotagasolina($notafac,$zona);

	if(empty($validarnota)){
		echo "<script> 
				alert('La nota o factura no existe');
				window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
			  </script>";
	}else{
		if($nuevo_disponible < 0){
			echo "<script> 
				alert('No se pudo otorgar el crédito ya que el cliente no cuenta con ese importe');
				window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
			  </script>";
		}else{
			//Validamos los no nulos
			if(strlen($fecha)<1){
				echo "<script> 
						alert('Falta la fecha del cliente');
						window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
					  </script>";
			}else{
				if(strlen($nombre)<1){
				echo "<script> 
						alert('Falta el nombre del cliente')
						window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
					  </script>";
				}else{
					if(strlen($domicilio)<1){
						echo "<script> 
								alert('Falta el domicilio');
								window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
							  </script>";
					}else{
						if(strlen($colonia)<1){
							echo "<script> 
									alert('Falta la colonia');
									window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
								  </script>";
						}else{
							if(strlen($notafac)<1){
								echo "<script> 
									alert('Falta el número de nota o factura');
									window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
								  </script>";
							}else{
								if(strlen($precio)<1){

									echo "<script> 
									alert('Falta el precio');
									window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
								  </script>";
								}else{
									if(strlen($litros)<1){
										echo "<script> 
												alert('Faltan los litros');
												window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
											  </script>";
									}else{
										if(strlen($importe)<1){
											echo "<script> 
												alert('Falta el importe');
												window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
											  </script>";
										}else{
											if(strlen($vencimiento)<1){
												echo "<script> 
												alert('Falta la fecha de vencimiento');
												window.location.href = '../../view/index.php?action=creditosgasolina/editar_credito.php&numfac=".$notafac."&name=".$idcliente."';
											  </script>";
											}else{
												$modelCredito->actualizarCreditoGasolina($fecha,$nombre,$domicilio,$colonia,$notafac,$precio,$litros,$importe,$vencimiento,$id,$vendedor,$zona,$dia,$mes,$anio,$tipo_credito);
												$modelCredito->actualizarCreditoCliente($nuevo_disponible,$idcliente,$new_used);
												echo "<script> 
														alert('Crédito modificado');
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
					
?>

 