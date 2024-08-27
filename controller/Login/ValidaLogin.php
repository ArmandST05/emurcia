<?php
include('../../model/ModelLogin.php');
include('../../model/ModelZona.php');
include('../../model/ModelPermiso.php');
include('../../model/BcryptHasher.php');

$modelLogin = new ModelLogin();
$modelZona = new ModelZona();
$modelPermiso = new ModelPermiso();

$usuarioNombre = trim($_POST["username"]);

//Se obtiene el usuario que intenta iniciar sesión
$usuario = $modelLogin->obtenerUsuario($usuarioNombre);

//Validamos que el usuario exista y su contraseña sea correcta para crear su sesión
$hasher = new BcryptHasher();

if (!empty($usuario) && $hasher->check(trim($_POST["password"]), $usuario["password"])
		&& $usuario["tipo_usuario"] != "cli" && $usuario["tipo_usuario"] != "ve") {//Los clientes app y vendedores solo acceden por medio de las apps
	if ($usuario["tipo_usuario"] != "su" && $usuario["tipo_usuario"] != "uc" && $usuario["tipo_usuario"] != "no") {
		$zona = $modelZona->buscarZonaNombre($usuario["zona"]);
		$zona = reset($zona);
		$permisos = $modelPermiso->obtenerPermisosZona($zona['idzona']);
		$permisos = array_column($permisos, 'nombre');

		$zonaId = $zona['idzona'];
		$zonaNombre = $usuario["zona"];
		$tipoZonaId = $zona['tipo_zona_id'];
	}else{
		$zonaId = "";
		$zonaNombre = "";
		$tipoZonaId = "";
		$permisos = [];
	}
	session_start();
	$_SESSION["id"] = $usuario["idusuario"];
	$_SESSION["user"] = $usuarioNombre;
	$_SESSION["tipoUsuario"] = $usuario["tipo_usuario"];
	$_SESSION["zona"] = $zonaNombre;
	$_SESSION["zonaId"] = $zonaId;
	$_SESSION["tipoZona"] = $tipoZonaId;
	$_SESSION["permisos"] = $permisos;

	$_SESSION["alerta-tipo"] = "null";
	$_SESSION["alerta-mensaje"] = "null";

	if (strcmp($_SESSION["tipoUsuario"], "su") == 0) {
		$_SESSION["superpass"] = "EMURCIA123";
		echo "<script> 
			window.location.href = '../../view/index.php?action=creditos/index.php';
		</script>";
	} else if (strcmp($_SESSION["tipoUsuario"], "ga") == 0) {
		echo "<script> 
			window.location.href = '../../view/index.php?action=presupuestosconceptos/index.php';
		</script>";
	} else if (strcmp($_SESSION["tipoUsuario"], "inv") == 0) {
		echo "<script> 
			window.location.href = '../../view/index.php?action=inventario/index_rutas.php';
		</script>";
	}else {
		echo "<script> 
			window.location.href = '../../view/index.php';
		</script>";
	}
} else {
	echo "<script> 
		alert('El usuario o contraseña son incorrectos.');
		window.location.href = '../../index.php';
	</script>";
}