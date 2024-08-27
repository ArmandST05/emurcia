<?php
include('../../model/BcryptHasher.php');

include('../../model/ModelLogin.php');
$modelLogin = new ModelLogin();

include('../../model/ModelZona.php');
$modelZona = new ModelZona();

$tipoUsuario = $_POST["tipoUsuario"];
$usuario = $_POST["usuario"];
$zonas = $_POST["zona"];

$zonaId = $zonas[0];

$zona = $modelZona->obtenerZonaId($zonaId);
$empleadoId = (isset($_POST["empleado"])) ? $_POST["empleado"]:null;
$zonaNombre = $zona["nombre"];

$hasher = new BcryptHasher();
$contrasena = $hasher->make($_POST["password"]);

$usuarioExistente = $modelLogin->obtenerUsuario($usuario);

if(empty($zonaNombre) && $tipoUsuario == "uc" || $tipoUsuario == "ud"){
	$zonaNombre = "Todas";
}

if(!empty($usuarioExistente)){
	echo "<script> 
		alert('El usuario ya existe, ingresa otro porfavor');
		window.location.href = '../../view/index.php?action=usuarios/nuevo.php';
	  </script>";
}
if(empty($usuario) || empty($contrasena) || (empty($zonaId) && $tipoUsuario == "u")){
	echo "<script> 
		alert('Ingresa todos los datos');
		window.location.href = '../../view/index.php?action=usuarios/nuevo.php';
	  </script>";
}else{
	$modelLogin->insertarUsuario($usuario,$contrasena,$zonaId,$zonaNombre,$tipoUsuario,$empleadoId,$zonas);

	echo "<script> 
		alert('Usuario creado');
		window.location.href = '../../view/index.php?action=usuarios/index.php';
		</script>";
}
?> 