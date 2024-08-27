<?php
	include('../../model/ModelCliente.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelCliente = new ModelCliente();

	/*Obtenemos los datos*/
	$nombrecliente = $_POST["name"];
	$domicilio = $_POST["dom"];
	$colonia = $_POST["col"];
	$tiponeg = $_POST["tipneg"];
	$credito = $_POST["credit"];
	$zona = $_POST["zone"];
	$pre = $_POST["pre"];

    $validarcliente = $modelCliente->verificarCliente($nombrecliente,$zona);


	if($nombrecliente=="VTA PUBLICO EN GRAL"){
		$modelCliente->insertar($nombrecliente,$domicilio,$colonia,$tiponeg,$credito,$zona,$pre);
				echo "<script> 
				alert('Cliente agregado');
				window.location.href = '../../view/index.php?action=clientes/index_credito.php'; 
				</script>";
	}
	else{
	 if(!empty($validarcliente)){
		echo "<script> 
				alert('El cliente ya existe en esta zona');
				window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
			  </script>";
	}

	else if(strlen($nombrecliente)<1){//Verificamos el nombre del cliente no sea nulo
	echo "<script> 
			alert('Falta el nombre del cliente');
			window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
		  </script>";
	}else{
		if(strlen($domicilio)<1){//Verificamos que domicilio no sea nulo
			echo "<script> 
					alert('Falta el domicilio');
					window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
				  </script>";
		}else{
			if(strlen($colonia)<1){//Verificamos que colonia no sea nulo
				echo "<script> 
						alert('Falta la colonia');
						window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
					  </script>";
			}else{
				if(strlen($tiponeg)<1){//Verificamos el tipo de negocio no sea nulo
					echo "<script> 
						alert('Falta el tipo del negocio');
						window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
					  </script>";
				}else{
					if(strlen($credito)<1){//Verificamos que contado tenga algo
						echo "<script> 
						alert('Falta el crédito otorgado');
						window.location.href = '../../view/index.php?action=clientes/nuevo_credito.php';
					  </script>";
					}else{//Todo esta bien, agregamos a la BD
						$modelCliente->insertar($nombrecliente,$domicilio,$colonia,$tiponeg,$credito,$zona,$pre);
						echo "<script> 
								alert('Cliente agregado');
								window.location.href = '../../view/index.php?action=clientes/index_credito.php'; 
							  </script>";
					}
				}
			}
		}
	  }
	}	

	
					
?>

 