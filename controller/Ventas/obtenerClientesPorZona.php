<?php
// Incluye la clase del modelo para acceder a la base de datos
include('../../model/ModelClientePedido.php');

$modelCliente = new ModelClientePedido();

if (isset($_GET['zona'])) { // Cambiado a $_GET
    $zonaId = $_GET['zona'];
    $clientes = $modelCliente->buscarClientesPorZona($zonaId);

    // Asegurarse de que no hay errores de PHP antes de este punto
    header('Content-Type: application/json'); // Asegurar que la respuesta sea JSON
    echo json_encode($clientes);  // Devolver los clientes en formato JSON
} else {
    echo json_encode([]);  // Si no se envía zona, devolver un array vacío
}
?>
