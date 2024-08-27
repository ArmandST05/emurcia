<?php
include '../../model/ModelMeta.php';
$modelMeta = new ModelMeta();


$tiposEmpleados = $modelMeta->obtenerTiposDeEmpleados();
    echo json_encode($tiposEmpleados);
?>