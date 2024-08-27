<?php
include('../../model/ModelReporte.php');
$model_rep = new ModelReporte();

$nombre = $_POST["nombre"];
$zona = $_POST["zona"];
$id_cli = $_POST["id_cli"];

$model_rep->crearPdfSaldoClientes($id_cli,$zona,$nombre);
?>