<?php
$modelReporte = new ModelReporte();
$modelCliente = new ModelCliente();
$zona = ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") ? $_POST["zona"] : $_SESSION["zonaId"];
$mes = date("m");
if ($mes == "0") {
    $mes = "12";
}
$d = date("d");
$m = date("m");
$a = date("Y");

$hoy = $a . "-" . $m . "-" . $d;
function dateDiff($time1, $time2)
{
    set_time_limit(0);
    $date1 = date_create($time1);
    $date2 = date_create($time2);

    $resultado = 0;
    $resultado = date_diff($date1, $date2);

    $resultado = $resultado->format("%a");
    return $resultado;
}
$sumacreditosrecuperados = $modelReporte->obtenersumarecuperadosfinmes($zona);
$sumacreditosotorgados = $modelReporte->obtenersumaotorgadosfinmes($zona);
$sumaabonos = $modelReporte->obtenersumaabonosfinmes($zona);
$otorgados = $modelReporte->obtenercreditosotorgadosfinmes($zona);
//$recuperados = $modelReporte->obtenercreditosrecuperadosfinmes($zona);
$sumaotorgadosinicial = $modelReporte->obtenersumaotorgados($zona);

$saldoo = $modelReporte->obtenersaldozonastorgados2($zona);
foreach ($saldoo as $key) {
    $salddo_gas = $key["total_chido"];
}
//Calculos
$saldototal = $sumacreditosotorgados - $sumaabonos;
$totalotorgados = $sumacreditosotorgados - $sumaotorgadosinicial;

$total = 0;
$totalp = 0;
$tot_rec = 0;
$cantidad_otorgada1 = 0;
$importe_pagado = 0;

foreach ($otorgados as $datos11) {
    $total += $datos11["importe"];
    $totalp += $datos11["importe_pagado"];
}

$hoy = date("Y-m");
$fecha = $hoy . "-01";

$rec_mes = $modelReporte->obtenercreditosotorgadosfinmesrecM($zona, $fecha);
foreach ($rec_mes as $rec1) {
    $tot_rec = $tot_rec + $rec1["cantidad"];
}
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="index.php?action=creditos/index.php">Créditos Gas</a> /
        <a href="index.php?action=creditos/generar_reportes.php">Reportes</a> /
        <a href="#">Relación de fin de mes</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Relación fin de mes <br><?php echo $zona ?></h1>
</div>
<!-- Content Row -->
<div id="dvData">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="info">
                                <table>
                                    <tr><strong><label id='saldo1'></label></strong></tr>
                                    <tr>
                                        <td><strong>Otorgado durante el mes: $<?php echo  number_format(($totalotorgados), 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Recuperado del mes: $<?php echo  number_format(($tot_rec), 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div clas="col-md-2">
                            <form action="../controller/Reportes/ReporteFinMes.php" method="POST">
                                <input type="hidden" name="zone" value="<?php echo $zona; ?>">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="far fa-file-pdf"></i>Guardar PDF</button>
                            </form>
                            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
                        </div>
                    </div>
                    <div class="row">
                        <table id="datosexcel" class="table table-sm table-bordered table-responsive" style="width:100%">
                            <thead>
                                <tr>
                                    <th> Fecha </th>
                                    <th> Nombre cliente </th>
                                    <th> Nombre Comercial </th>
                                    <th> Colonia </th>
                                    <th> No. factura </th>
                                    <th> Folio Fiscal </th>
                                    <th> Precio litro </th>
                                    <th> Litros </th>
                                    <th> Importe </th>
                                    <th> Importe Pagado</th>
                                    <th> Descuento </th>
                                    <th> Vencimiento </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($otorgados as $datos) :

                                    $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"];
                                    $importe_pagado = $importe_pagado + $datos["importe_pagado"];
                                    $interval = dateDiff($hoy, $datos["fecha"]);

                                    $backgroundColor = "#3ADF00";
                                    $fontColor = "black";
                                    if ($interval >= 10  && $interval <= 20) {
                                        //Naranja
                                        $backgroundColor = "#FF8000";
                                        $fontColor = "black";
                                    } else if ($interval >= 20) {
                                        //Rojo
                                        $backgroundColor = "#FA5858";
                                        $fontColor = "white";
                                    }

                                    $cliente = $modelCliente->obtenerPorId($datos['id_cliente']);
                                ?>
                                    <td style="background-color:<?php echo $backgroundColor; ?>; color:#F7EC4A;"><b>
                                            <font color="<?php echo $fontColor; ?>"><?php echo $datos["fecha"] ?>
                                        </b></td>
                                    <td><?php echo $datos["nombre"] ?></td>
                                    <td><?php echo $cliente["nombre_comercial"] ?></td>
                                    <td><?php echo $datos["colonia"] ?></td>
                                    <td><?php echo $datos["num_factura"] ?></td>
                                    <td><?php echo $datos["folio_fiscal"] ?></td>
                                    <td> $ <?php echo $datos["precio_litro"] ?></td>
                                    <td><?php echo $datos["litros"] ?></td>
                                    <td> $ <?php echo $datos["importe"] ?></td>
                                    <td> $ <?php echo $datos["importe_pagado"] ?></td>
                                    <td><?php echo $datos["descuento"] ?></td>
                                    <td><?php echo $datos["fecha_vencimiento"] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id='saldo' value='<?php echo  number_format($cantidad_otorgada1 - $importe_pagado); ?>' readonly size="10">
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/JavaScript">
    $(document).ready(function () {
        agregarTotales();
    });

    $('#datosexcel').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

    $("#btnExport").click(function (e) {
        $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel"
                , datatype: $datatype.Table
                , filename: 'creditosfinmesgasolina'
        });
    });

    function agregarTotales()
    {
        $("#saldo1").text(" Saldo: $" + $("#saldo").val());
    }
</script>