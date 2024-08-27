<?php
session_start();
if(!empty($_SESSION["user"])){
	if(strcmp($_SESSION["tipoUsuario"], "uc") == 0 || strcmp($_SESSION["tipoUsuario"], "su") == 0){
		
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