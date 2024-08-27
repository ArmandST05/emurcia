<?php
	include('../../model/ModelSmsEnviado.php');  
    $modelSmsEnviado = new ModelSmsEnviado();
	date_default_timezone_set('America/Mexico_City');

	$fecha = $_GET["fecha"];
	//DIRECCIÓN 1(ENVIADO), 2 (RECIBIDO O RESPUESTA)
	//ESTATUS 1(ENVIADO),2(RECIBIDO),3(NO ENTREGADO)
	//MÓDULOS 1(PEDIDOS),2 (PRÓXIMOS PEDIDOS)
	$contenido = "EL CONTENIDO DEL MENSAJE NO SE PUEDO RECUPERAR";
	$i = 0;
	while($i <= $_GET["limite"]){
		$modelSmsEnviado->insertar(0,0,0,1,1,$_GET['zonaId'],$contenido,$fecha,2); 
		$i++;
	}
	echo "Datos registrados";
?>