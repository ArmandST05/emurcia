<?php
    include('../../model/ModelAutoconsumo.php');	
    /*variable para llamar metodo de Modelo*/
    $modelAutoconsumo = new ModelAutoconsumo();
    date_default_timezone_set('America/Mexico_City');

    /*Obtenemos los datos*/
    $diaIni =  ($_POST["diaIni"]) ? $_POST["diaIni"] : date("d");
    $mesIni =  ($_POST["mesIni"]) ? $_POST["mesIni"] : date("m");
    $anioIni =  ($_POST["anioIni"]) ? $_POST["anioIni"] : date("Y");

    $diaFin =  ($_POST["diaFin"]) ? $_POST["diaFin"] : date("d");
    $mesFin =  ($_POST["mesFin"]) ? $_POST["mesFin"] : date("m");
    $anioFin =  ($_POST["anioFin"]) ? $_POST["anioFin"] : date("Y");

    $fechaInicio = $anioIni."-".$mesIni."-".$diaIni; 
    $fechaFin = $anioFin."-".$mesFin."-".$diaFin; 

    $rutaId = $_POST["ruta"];
    $combustible = $_POST["combustible"];
    $litros = $_POST["litros"];
    $costo_litro = $_POST["costo_litro"];
    $kmi = $_POST["kmi"];
    $kmf = $_POST["kmf"];
    
    // Validar si se subió un archivo
    $comprobante = isset($_FILES["comprobante"]) ? $_FILES["comprobante"] : null;

    //Validar no nulos
    if (empty($rutaId) || empty($combustible) || empty($litros) || empty($costo_litro) || empty($kmi) || empty($kmf) || empty($comprobante)) {
		echo "<script>
			alert('Ingresa todos los datos y sube el comprobante por favor');
			window.location.href = '../../view/index.php?action=autoconsumos/nuevo.php';
		</script>";
		exit();
	} 
	 else {
        // Llamar al método insertar pasando el comprobante
        try {
            $autoconsumo = $modelAutoconsumo->insertar($rutaId, $combustible, $litros, $costo_litro, $kmi, $kmf, $fechaInicio, $fechaFin, $comprobante);
            
            if ($autoconsumo) {
                echo "<script> 
                    alert('Autoconsumo agregado correctamente'); 
                    window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
                </script>";
            } else {
                echo "<script> 
                    alert('Ha ocurrido un error al agregar el autoconsumo'); 
                    window.location.href = '../../view/index.php?action=autoconsumos/index.php'; 
                </script>";
            }
        } catch (Exception $e) {
            // Manejo de error
            echo "<script> 
                alert('Error: " . $e->getMessage() . "'); 
                window.location.href = '../../view/index.php?action=autoconsumos/nuevo.php'; 
            </script>";
        }
    }
?>
