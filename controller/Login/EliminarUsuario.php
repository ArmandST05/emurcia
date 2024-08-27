<?php
include('../../model/ModelLogin.php');
$modelLogin = new ModelLogin();

$id = $_POST["id"];
$modelLogin->eliminar_usuario($id);
?> 