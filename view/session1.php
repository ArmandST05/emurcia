<?php
session_start();
if(!empty($_SESSION["user"])){
	//Verificamos que el tipo de usuario sea "su-u-uc" para darle solo acceso a superusuarios, usuarios de zona y usuarios de consulta (auditores)
	if(strcmp($_SESSION["tipoUsuario"], "u") == 0 || strcmp($_SESSION["tipoUsuario"], "su") == 0 || strcmp($_SESSION["tipoUsuario"], "uc") == 0 || strcmp($_SESSION["tipoUsuario"], "no") == 0 || strcmp($_SESSION["tipoUsuario"], "mp") == 0 || strcmp($_SESSION["tipoUsuario"], "mv") == 0 || strcmp($_SESSION["tipoUsuario"], "ga") == 0 || strcmp($_SESSION["tipoUsuario"], "inv") == 0){

	}else{
		echo "<script> 
				alert('No tienes permiso para acceder a esta sección');
				window.location.href = '/view/index.php';
			  </script>";
	}
}else{
	echo "<script> 
		alert('¡Inicia sesión por favor!');
		window.location.href = '/';
	</script>";
}

?>