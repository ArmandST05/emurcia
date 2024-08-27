<?php
	include('../../model/ModelCredito.php');
	$mode_credit = new ModelCredito();

	//Obtenemos los datos
	$fecha = $_GET["formfecha"];
	$idcliente = $_GET["idcliente"];
	$nombre = $_GET["name"];
	$notafac = $_GET["nota"];
	$importe = $_GET["imp"];
	$zona = $_GET["zone"];
    $dia = $_GET["day"];
	$mes = $_GET["month"];
	$anio = $_GET["year"];
	$idabono = $_GET["id_credit"];
	$fechaabono = $_GET["fechaabono"];

	//Verificamos que la nota no exista
	$validarnota = $mode_credit->verificarnota_rec($notafac,$zona);

	if(empty($validarnota)){
		echo "<script> 
				alert('El abono no existe');
				window.location.href = '../../view/creditos/mod_info_cred_rec.php?numfac=".$notafac."&name=".$idcliente."';
			  </script>";
	}else{
		if(strlen($fecha)<1){
			echo "<script> 
						alert('Falta la fecha');
						window.location.href = '../../view/creditos/mod_info_cred_rec.php?numfac=".$notafac."&name=".$idcliente."';
					  </script>";
		}else{
			if(strlen($notafac)<1){
				echo "<script> 
						alert('Falta el numero de nota o factura');
						window.location.href = '../../view/creditos/mod_info_cred_rec.php?numfac=".$notafac."&name=".$idcliente."';
					  </script>";
			}else{
				if(strlen($importe)<1){
					echo "<script> 
							alert('Falta el importe');
							window.location.href = '../../view/creditos/mod_info_cred_rec.php?numfac=".$notafac."&name=".$idcliente."';
						  </script>";
				}else{
					if(strlen($nombre)<1){
						echo "<script> 
								alert('Falta el nombre del cliente')
								window.location.href = '../../view/creditos/mod_info_cred_rec.php?numfac=".$notafac."&name=".$idcliente."';
				  			  </script>";
					}else{
							$mode_credit->mod_abono($idabono,$fecha,$nombre,$notafac,$importe,$idabono,$zona,$dia,$mes,$anio,$idcliente);
						echo "<script> 
								alert('Credito modificado');
								window.location.href = '../../view/creditos/index_creditos.php'; 
							  </script>";
						}
					}
				}
			}
		}
?>
