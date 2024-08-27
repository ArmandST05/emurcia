<?php
	include('../../model/ModelCliente.php');	
  	/*variable para llamar metodo de Modelo*/
	$mode_cliente = new ModelCliente();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$nombrecliente = $_POST["nomcli"];
	$domicilio = $_POST["dom"];
	$colonia = $_POST["col"];
	$tiponeg = $_POST["tipneg"];
	$credito = $_POST["credit"];
	$nuevodisp = $_POST["new_disp"];
	$pre=$_POST["pre"];

	if(strlen($nombrecliente)<1){//Verificamos el nombre del cliente no sea nulo
	echo "<script> 
			alert('Falta el nombre del cliente');
			window.location.href = '../../view/index.php?action=clientes/index_credito.php&cliente=".$_POST["id"]."';
		  </script>";
	}else{
		if(strlen($domicilio)<1){//Verificamos que domicilio no sea nulo
			echo "<script> 
					alert('Falta el domicilio');
					window.location.href = '../../view/index.php?action=clientes/index_credito.php&cliente=".$_POST["id"]."';
				  </script>";
		}else{
			if(strlen($colonia)<1){//Verificamos que colonia no sea nulo
				echo "<script> 
						alert('Falta la colonia');
						window.location.href = '../../view/index.php?action=clientes/index_credito.php&cliente=".$_POST["id"]."';
					  </script>";
			}else{
				if(strlen($tiponeg)<1){//Verificamos el tipo de negocio no sea nulo
					echo "<script> 
						alert('Falta el tipo del negocio');
						window.location.href = '../../view/index.php?action=clientes/index_credito.php&cliente=".$_POST["id"]."';
					  </script>";
				}else{
					if(strlen($credito)<1){//Verificamos que contado tenga algo
						
						echo "<script> 
						alert('Falta el credito otorgado');
						window.location.href = '../../view/index.php?action=clientes/index_credito.php&cliente=".$_POST["id"]."';
					  </script>";
					}else{//Todo esta bien, agregamos a la BD
						$mode_cliente->actualizarCliente($id,$nombrecliente,$domicilio,$colonia,$tiponeg,$credito,$nuevodisp,$pre);

						echo "<script> 
								alert('Cliente actualizado');
								window.location.href = '../../view/index.php?action=clientes/index_credito.php'; 
							  </script>";
					}
				}
			}
		}
	}	

	
?>

