<?php
include('../../model/ModelPermiso.php');
$modelPermiso = new ModelPermiso();

$rutaId = $_GET["rutaId"];
$permisosRuta = $modelPermiso->listaPorRuta($rutaId);
$permisosRuta = array_column($permisosRuta,'nombre');

echo json_encode($permisosRuta);
?>