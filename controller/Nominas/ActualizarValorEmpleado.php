<?php
include('../../model/ModelNomina.php');
/* Variable para llamar al método del modelo */
$modelNomina = new ModelNomina();

/* Obtenemos los datos del formulario */
$nominaId = $_POST["nominaId"];
$empleadoId = $_POST["empleadoId"];
$columnaNombre = $_POST["columnaNombre"];
$valor = ($_POST["valor"]) ? $_POST["valor"]:0;

if (empty($nominaId) || empty($empleadoId) || empty($columnaNombre)) {
    // Verificamos que los datos no sean nulos o vacíos.
    return http_response_code(500);
}else{
    $actualizar = $modelNomina->actualizarValorEmpleado($nominaId,$empleadoId,$columnaNombre,$valor);
    if($actualizar){
        return http_response_code(200);
    }else{
        return http_response_code(500);
    }
}
