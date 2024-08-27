<?php
	include('../../model/ModelClienteDescuento.php');	
  	/*Variable para llamar método de Modelo*/
	$modelCliente = new ModelClienteDescuento();

	/*Obtenemos los datos*/
	$nombre = $_POST["nombre"];
	$giro = $_POST["giro"];
	$calle = $_POST["calle"];
	$numero = $_POST["numero"];
	$colonia = $_POST["colonia"];
	$municipio = $_POST["municipio"];
	$zonaId = $_POST["zonaId"];
	$descuentoId = $_POST["descuentoId"];
    
    $validarCliente = $modelCliente->verificarNombre($nombre,$zonaId);

	if(!empty($validarCliente)){
		echo "<script> 
				alert('El cliente ya existe en esta zona');
				window.location.href = '../../view/clientesdescuento/nuevo.php';
			  </script>";
	}else if(strlen($nombre)<1 || strlen($giro)<1 || strlen($calle)<1 || strlen($numero)<1 || strlen($colonia)<1 || strlen($municipio)<1
	|| strlen($zonaId)<1 || strlen($descuentoId)<1){//Verificamos que los datos no sean nulos
	echo "<script> 
			alert('Completa los datos');
			window.location.href = '../../view/index.php?action=clientesdescuento/index.php&zonaId=".$zonaId."';
		  </script>";
	}else{//Todo esta bien, agregamos a la BD
		$nuevoCliente = $modelCliente->insertar($nombre,$giro, $calle, $numero, $colonia, $municipio, $zonaId, $descuentoId);
		echo "<script> 
				alert('Cliente agregado');
				window.location.href = '../../view/index.php?action=clientesdescuento/index.php&zonaId=".$zonaId."'; 
				</script>";
				
	}
					
?>

 