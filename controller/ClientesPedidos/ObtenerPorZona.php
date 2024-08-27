<?php
include("../../model/ModelClientePedido.php");
$modelClientePedido = new ModelClientePedido;

// Almacenar la petición (ie, get/post) en una variable array global
$requestData = $_REQUEST;
$zonaId = $_REQUEST["zonaId"];

// Obtener el total de filas sin ninguna búsqueda
$totalClientes = $modelClientePedido->totalPorZona($zonaId);
$totalData = $totalClientes["total"];
$totalFiltered = $totalData;  // Cuando no hay parámetro de búsqueda el total de filas = total de filas filtradas.

// $requestData['order'][0]['column'] Contiene el índice de la columna, 
//$requestData['order'][0]['dir'] Contiene el orden por ejemplo asc/desc
//$requestData['start'] Contiene el número de la fila inicial
//$requestData['length'] Contiene el limite de datos.

$limiteInicial = $requestData['start'];
$limiteFinal = $requestData['length'];

//BUSCAR DATOS
if (!empty($requestData['search']['value'])) {
	// Si hay un parámetro de búsqueda
	$valor = $requestData['search']['value'];

	//Cuando hay un parámetro de búsqueda se modifica el total de filas filtradas por búsqueda pero sin el límite de la consulta
	$totalFiltradoClientes = $modelClientePedido->totalPorZonaFiltrado($zonaId,$valor);
	$totalFiltered = $totalFiltradoClientes["total"];

	//Se obtienen los datos con el límite 
	$data = $modelClientePedido->listaPorZonaFiltradoLimite($zonaId,$valor,$limiteInicial,$limiteFinal);
} else {
	//Se obtienen todos los datos con límite 
	$data = $modelClientePedido->listaPorZonaLimite($zonaId,$limiteInicial,$limiteFinal);
}


$json_data = array(
	"draw"            => intval($requestData['draw']),// Para cada petición (request/draw) del lado del cliente, ellos envían un número como parámetro, cuando ellos reciben una respuesta response/data primero revisan el número de petición (draw number), así que se envía el mismo número de petición (draw). 
	"recordsTotal"    => intval($totalData),  // Número total de filas
	"recordsFiltered" => intval($totalFiltered), // Número total de filas después de la búsqueda, si no hay búsqueda entonces totalFiltered = totalData
	"data"            => $data   // Array total de datos
);

echo json_encode($json_data);  // Enviar datos en formato json
