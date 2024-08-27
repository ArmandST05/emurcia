<?php
include('../../model/ModelProducto.php');
include('../../model/ModelPrecioProducto.php');
include('../../view/session1.php');
$modelProducto = new ModelProducto();
$modelPrecioProducto = new ModelPrecioProducto();

date_default_timezone_set('America/Mexico_City');

$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$zona = $_SESSION["zona"];
$zonaId = $_SESSION["zonaId"];
$anio = date("Y");
$precioLts = 0;
$precioKg = 0;

//Obtener precio mes actual para los litros de gas
$mes = $meses[date("m")];
$precioData = $modelPrecioProducto->obtenerPrecioGasZonaMes($anio, $mes, $zonaId,0);

if (!empty($precioData)) {
    $precioData = reset($precioData);

    $precioLts = $precioData["precio_litro"];
    $precioKg = $precioData["precio_kilo"];
}

$productoLts["nombre"] = "Lts";
$productoLts["precio"] = $precioLts;

$productoKg["nombre"] = "Kg";
$productoKg["precio"] = $precioKg;

array_push($productos, $productoLts,$productoKg);

//Obtener los productos de tipo cilindros
$productosCilindros = $modelProducto->obtenerCilindros();
$productosCilindros = reset($productosCilindros);

foreach ($productosCilindros as $cilindro) {
    $productoData = [];
    $precioCilindro = 0;
    $mes = date("m");
    //Para los cilindros se toma el precio para el cliente asignado por el administrador. 
    //Así el administrador controla redondeo de decimales.

    $precioData = $modelProducto->obtenerPrecioMesProducto($anio, $mes, $zonaId, $productoId);
    if (!empty($precioData)) {
        $precioData = reset($precioData);
        $precioCilindro = $precioData["precio"];
    }
    $productoData["nombre"] = $cilidro["nombre"];
    $productoData["precio"] = $precioCilindro;

    array_push($productos, $productoData);
}

echo json_encode($productos);
