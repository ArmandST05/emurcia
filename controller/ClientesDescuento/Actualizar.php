<?php
	include('../../model/ModelClienteDescuento.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelCliente = new ModelClienteDescuento();

	/*Obtenemos los datos*/
	$id = $_POST["id"];
	$nombre = $_POST["nombre"];
	$giro = $_POST["giro"];
	$calle = $_POST["calle"];
	$numero = $_POST["numero"];
	$colonia = $_POST["colonia"];
	$municipio = $_POST["municipio"];
	$zonaId = $_POST["zonaId"];
	$descuentoId = $_POST["descuentoId"];

	if(strlen($id)<1 || strlen($nombre)<1 || strlen($giro)<1 || strlen($calle)<1 || strlen($numero)<1 || strlen($colonia)<1 || strlen($municipio)<1
	|| strlen($zonaId)<1 || strlen($descuentoId)<1){//Verificamos que los datos no sean nulos
	echo "<script> 
			alert('Completa los datos');
			window.location.href = '../../view/index.php?action=clientesdescuento/index.php&zonaId=".$zonaId."';
		  </script>";
	}else{//Todo esta bien, agregamos a la BD
		$nuevoCliente = $modelCliente->actualizar($id,$nombre,$giro, $calle, $numero, $colonia, $municipio, $zonaId, $descuentoId);
		echo "<script> 
				alert('Cliente actualizado');
				window.location.href = '../../view/index.php?action=clientesdescuento/index.php&zonaId=".$zonaId."'; 
				</script>";
	}
?>

