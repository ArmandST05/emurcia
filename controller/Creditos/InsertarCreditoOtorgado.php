<?php
	include('../../model/ModelCredito.php');
	$mode_credit = new ModelCredito();

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
	$descuento = $_POST["desc"];
	$vencimiento = $_POST["formatofecha"];
	$disponible = $_POST["disp"];
	$usado = $_POST["used"];
	$vendedor = $_POST["salesman"];
	$zona = $_POST["zone"];
	$dia = $_POST["day"];
	$mes = $_POST["month"];
	$anio = $_POST["year"];

	$pre_des=$_POST["pre_des"];
    
    if($pre_des==0){
    $precio_chi=$precio;
    }else{
	$precio_chi=($precio) -($precio - $pre_des) ;
    }


	//Calculo de nuevos creditos
	$nuevo_credito = $disponible - $importe;
	$new_used = $usado + $importe;
	//Verificamos que la nota no exista
	$validarnota = $mode_credit->verificarnota($notafac,$zona,$foliofisc);

	if(!empty($validarnota)){
		echo "<script> 
				alert('La nota o factura ya existe');
				window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';

			  </script>";
	}else{
		if($nuevo_credito < 0){
			echo "<script> 
				alert('No se pudo otorgar el crédito ya que el cliente no cuenta con ese importe');
				window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
			  </script>";
		}else{
			//Validamos los no nulos
			if(strlen($fecha)<1){
				echo "<script> 
						alert('Falta la fecha del cliente');
						window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
					  </script>";
			}else{
				if(strlen($nombre)<1){
				echo "<script> 
						alert('Falta el nombre del cliente')
						window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
					  </script>";
				}else{
					if(strlen($domicilio)<1){
						echo "<script> 
								alert('Falta el domicilio');
								window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
							  </script>";
					}else{
						if(strlen($colonia)<1){
							echo "<script> 
									alert('Falta la colonia');
									window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
								  </script>";
						}else{
							if(strlen($notafac)<1){
								echo "<script> 
									alert('Falta el numero de nota o factura');
									window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
								  </script>";
							}else{
								if(strlen($precio)<1){

									echo "<script> 
									alert('Falta el precio');
									window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
								  </script>";
								}else{
									if(strlen($litros)<1){
										echo "<script> 
												alert('Faltan los litros');
												window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
											  </script>";
									}else{
										if(strlen($importe)<1){
											echo "<script> 
												alert('Falta el importe');
												window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
											  </script>";
										}else{
											if(strlen($vencimiento)<1){
												echo "<script> 
												alert('Falta la fecha de vencimiento');
												window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
											  </script>";
											}else{
												if(strlen($foliofisc)<1){
													echo "<script> 
															alert('Falta el folio fiscal');
															window.location.href = '../../view/index.php?action=creditos/capturar_otorgado_cliente.php&cliente=".$idcliente."';
														  </script>";
												}else{
												$mode_credit->addcredit($idcliente,$fecha,$nombre,$domicilio,$colonia,$notafac,$foliofisc,$precio_chi,$litros,$importe,$vencimiento,$vendedor,$zona,$dia,$mes,$anio,$descuento);
												$mode_credit->updatecredit($nuevo_credito,$idcliente,$new_used);
												echo "<script> 
														alert('Crédito registrado');
														window.location.href = '../../view/index.php?action=creditos/index.php';	
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
					
?>