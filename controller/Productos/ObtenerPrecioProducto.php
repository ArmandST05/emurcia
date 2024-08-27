<?php
include('../../view/session1.php');
include('../../model/ModelProducto.php');
$modelProducto = new ModelProducto();
include('../../model/ModelPrecioProducto.php');
$modelPrecioProducto = new ModelPrecioProducto();
include('../../model/ModelZona.php');
$modelZona = new ModelZona();
date_default_timezone_set('America/Mexico_City');

$meses = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];


if ($_SESSION["tipoUsuario"] == "su") {
    $zonaData = $modelZona->obtenerZonaId($_GET["zonaId"]);
    $tipoZonaId = floatval($zonaData["tipo_zona_id"]);
    $zonaId = $zonaData["idzona"];
}else{
    $tipoZonaId = $_SESSION["tipoZona"];
    $zonaId = $_SESSION["zonaId"];
}
$productoId = $_GET["productoId"];
$anio = date("Y");
$mes = date("n");
$precioProducto = 0;

//Obtener el producto
$producto = $modelProducto->productoPorId($productoId);
$producto = reset($producto);

//Zona Gas == 1
//Zona Gasolina == 2 //
if ($tipoZonaId == 1) {

    //EN LOS PRODUCTOS DE ZONAS GAS, EL PRECIO PUEDE VARIAR DEPENDIENDO DE LA RUTA/ESTACIÓN.
    $rutaId = $_GET["rutaId"];

    //Obtener precio mes actual para los litros de gas
    if ($producto["idproducto"] == "4") {
        //Si se le asignó un precio especial a la ruta se obtiene
        $precioData = $modelPrecioProducto->obtenerPrecioGasZonaMes($anio, $mes, $zonaId,$rutaId);
        if(empty($precioData)){
            //Si no se asignó un precio por ruta, se obtiene el precio general de la zona.
            $precioData = $modelPrecioProducto->obtenerPrecioGasZonaMes($anio, $mes, $zonaId,0);
        }

        if (!empty($precioData)) {
            $precioData = reset($precioData);
            $precioProducto = $precioData["precio_litro"];
        }
    } else {
        //Para los cilindros se toma el precio para el cliente asignado por el administrador. 
        //Así el administrador controla redondeo de decimales.

        //Si se le asignó un precio especial a la ruta se obtiene
        $precioData = $modelProducto->obtenerPrecioMesProducto($anio, $mes, $zonaId,$rutaId,$productoId);
        if(empty($precioData)){
            //Si no se asignó un precio por ruta, se obtiene el precio general de la zona.
            $precioData = $modelProducto->obtenerPrecioMesProducto($anio, $mes, $zonaId,0,$productoId);
        }

        if (!empty($precioData)) {
            $precioData = reset($precioData);
            $precioProducto = $precioData["precio"];
        }
    }
}
else if($tipoZonaId == 2){
    $precioData = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaId,$productoId);
    $precioData = reset($precioData);
    $precioProducto = $precioData['precio'];
}

echo json_encode($precioProducto);
