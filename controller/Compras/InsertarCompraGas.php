<?php
	include('../../view/session1.php');
	include('../../model/ModelCompra.php');	
  	/*variable para llamar metodo de Modelo*/
	$modelComprasGas = new ModelCompra();
    date_default_timezone_set('America/Mexico_City');

	/*Obtenemos los datos*/
	$nocompra = $_POST["nocompra"];
	$nocompra = trim($nocompra);
	$nombrep = $_POST["nombrep"];
	$destino = $_POST["destino"];
	$kilos = $_POST["kilos"];
	$origen = $_POST["origen"];
    
    $diaIniPago = ($_POST["diaini"]) ? $_POST["diaini"]: date("d");
    $mesIniPago = ($_POST["mesini"]) ? $_POST["mesini"]: date("m");
    $anioIniPago = ($_POST["anioini"]) ? $_POST["anioini"]: date("Y");
    
    $fecha = $anioIniPago."/".$mesIniPago."/".$diaIniPago;

    $diaDescarga = ($_POST["diainie"]) ? $_POST["diainie"]: date("d");
    $mesDescarga = ($_POST["mesinie"]) ? $_POST["mesinie"]: date("m");
    $anioDescarga = ($_POST["anioinie"]) ? $_POST["anioinie"]: date("Y");
    
    $fechae = $anioDescarga."/".$mesDescarga."/".$diaDescarga;//Fecha Descarga

    if ($_SESSION["tipoUsuario"] == "su"){
		$zonaId = $_POST["zona"];
	}else{
		$zonaId = $_SESSION["zonaId"];
	}
    $densidad=$_POST["densidad"];
    $litros=$_POST["litros"];
    $descarga=$_POST["zonadescarga"];

    $fechapago=date('Y-m-d',strtotime('+20 days', strtotime($fecha)));

    $valida="0";
    $validar =$modelComprasGas->validarCompra($zonaId,$nocompra);
    foreach($validar as $datos){
	        $valida =$datos["numero"];
	    }

    if ($valida == "0"){
    	 $modelComprasGas->insertarCompraGas($nocompra,$nombrep,$destino,$kilos,$origen,$fecha,$zonaId,$fechae,$fechapago,$densidad,$litros,$descarga);

    echo "<script> alert('Compra registrada');
            window.location.href = '../../view/index.php?action=comprasgas/index.php'; 
        </script>";
    }
    else
    {
    	echo "<script> alert('Ya existe el número de compra en esta zona');
                window.location.href = '../../view/index.php?action=comprasgas/index.php'; 
            </script>"; 	
    }
?>