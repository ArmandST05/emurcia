<?php
require_once '../../model/ModelTraspaso.php'; // Ajusta la ruta si es necesario

header('Content-Type: application/json');

if (isset($_POST['zonaId'])) {
    $zonaId = $_POST['zonaId'];

    $modelTraspaso = new ModelTraspaso();
    $estaciones = $modelTraspaso->obtenerEstaciones($zonaId);

    echo json_encode([
        'success' => true,
        'data' => $estaciones
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No se recibió zonaId'
    ]);
}
