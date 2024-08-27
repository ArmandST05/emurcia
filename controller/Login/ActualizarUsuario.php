<?php
include('../../model/BcryptHasher.php');

include('../../model/ModelLogin.php');
$modelLogin = new ModelLogin();

include('../../model/ModelZona.php');
$modelZona = new ModelZona();

$usuario = $modelLogin->obtenerUsuarioId($_POST["id"]);
$tipoUsuario = $_POST["tipoUsuario"];
$usuarioNombre = $_POST["usuario"];
$id = $_POST["id"];
$zonas = $_POST["zona"];
$zonaId = $zonas[0];

$empleadoId = (isset($_POST["empleado"])) ? $_POST["empleado"]:null;
$contrasena = null;

if(!empty(trim($_POST["password"]))){
	//Si se especificó una nueva contraseña, actualizar
	$hasher = new BcryptHasher();
	$contrasena= $hasher->make($_POST["password"]);
}else{
	//Si no se asignó una nueva contraseña, mantener original
	$contrasena= $usuario["password"];
}

if(empty($_POST["zona"]) || strcmp($tipoUsuario, "uc") == 0){
	$zonaNombre = "Todas";
}
else{
	$zona = $modelZona->obtenerZonaId($zonaId);
	$zonaNombre = $zona["nombre"];
}

$modelLogin->actualizar($usuarioNombre,$contrasena,$zonaId,$zonaNombre,$tipoUsuario,$id,$empleadoId,$zonas);

echo "<script> 
		alert('Usuario modificado');
		window.location.href = '../../view/index.php?action=usuarios/index.php';
	  </script>";



