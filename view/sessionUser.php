<?php
if(!empty($_SESSION["user"])){
	//Verificamos que el tipo de usuario sea "su" para darle solo acceso a los superusuarios
	if(strcmp($_SESSION["tipoUsuario"], "u") == 0 || strcmp($_SESSION["tipoUsuario"], "su") == 0){

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