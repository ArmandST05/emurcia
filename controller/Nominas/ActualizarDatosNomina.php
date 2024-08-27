<?php
try{
    include('../../model/ModelNomina.php');
    /* Variable para llamar al método del modelo */
    $modelNomina = new ModelNomina();

    /* Obtenemos los datos del formulario */
    $nominaId = $_POST["nominaId"];
    $total = $_POST["total"];
    $banco = $_POST["banco"];
    $efectivo = $_POST["efectivo"];
    $observaciones = $_POST["observaciones"];

    if (empty($nominaId)) {
        // Verificamos que los datos no sean nulos o vacíos.
        return http_response_code(500);
    }else{
        $actualizar = $modelNomina->actualizarNomina($nominaId,$total,$banco,$efectivo,$observaciones);
        return http_response_code(200);
    }
}catch(Exception $error){
    return http_response_code(500);
}
