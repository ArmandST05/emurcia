<?php
	include('../../model/ModelSmsEnviado.php');  
    $modelSmsEnviado = new ModelSmsEnviado();
	date_default_timezone_set('America/Mexico_City');

	require '../../vendor/autoload.php';

	$fecha = date('Y-m-d H:i:s');
	
	//Envío de SMS https://docs-latam.wavy.global/documentacion-tecnica/api-integraciones/sms-api
	/*
	curl -X POST \
		https://api-messaging.wavy.global/v1/send-sms \
		-H 'authenticationtoken: <authenticationtoken>' \
		-H 'username: <username>' \
		-H 'content-type: application/json' \
		-d '{"destination": "5511900000000" , "messageText": "linha\nquebrada"}'
	
	*/
	$client = new \GuzzleHttp\Client();
	$response = $client->request(
		'POST',
		'https://api-messaging.wavy.global/v1/send-sms',
		[
			'headers' => [
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'username' => 'grupoemurcia',
				'authenticationToken' => 'GI6pogyoJU2bZAWV4YlOTPZybK6J4BFv1kLBJ6-R',
			],
			'body' => json_encode(array(
				"destination" => $_POST['telefono'],
				"messageText" => $_POST['contenido'],
			))
		]
	);
	//response->getHeaderLine('content-type');//$response->getBody(); 
	if($response->getStatusCode() == 200){
		$modelSmsEnviado->insertar($_POST['pedidoId'],$_POST['rutaId'],$_POST['clienteId'],$_POST['tipoDireccionSmsId'],$_POST['estatusSmsId'],$_POST['zonaId'],$_POST['contenido'],$fecha,$_POST['moduloEnvioId']); 
		//DIRECCIÓN 1(ENVIADO), 2 (RECIBIDO O RESPUESTA)
		//ESTATUS 1(ENVIADO),2(RECIBIDO),3(NO ENTREGADO)
		//MÓDULOS 1(PEDIDOS),2 (PRÓXIMOS PEDIDOS)
	}else{
		return http_response_code(500);
	}

	
?>