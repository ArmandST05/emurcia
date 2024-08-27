<?php
$modelReporte = new ModelReporte();
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

$sumacreditosrecuperados = $modelReporte->obtenersumarecuperadosfinmesgasolina($zona);
$sumacreditosotorgados = $modelReporte->obtenersumaotorgadosfinmesgasolina($zona);
$sumaabonos = $modelReporte->obtenersumaabonosfinmes($zona);
$otorgados = $modelReporte->obtenercreditosotorgadosfinmesgasolina($zona);
$recuperados = $modelReporte->obtenercreditosrecuperadosfinmesgasolina($zona);
$sumaotorgadosinicial = $modelReporte->obtenersumaotorgadosgasolina($zona);

//Cálculos
$saldo = $modelReporte->obtenersaldozonastorgados($zona);
foreach ($saldo as $key) {
    $saldo_total=$key["total"];
}
$saldototal = $sumacreditosotorgados - $sumaabonos;
$totalotorgados = $sumacreditosotorgados - $sumaotorgadosinicial;

$total = 0;
$totalp = 0;
$cantidad_otorgada1 = 0;
$importe_pagado = 0;
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
        <a href="index.php?action=creditosgasolina/generar_reportes.php">Reportes</a> /
        <a href="#">Relación de Fin de mes</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Relación Fin de mes <br><?php echo $zona ?></h1>
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
                                <tr><td><strong>Saldo: $<?php echo number_format(($saldo_total),2);?></td></tr>
                                <tr><td><strong>Otorgado durante el mes: $<?php echo  number_format (( $totalotorgados),2);?></td></tr>
                                <tr><td><strong>Recuperado: $<?php echo  number_format(($sumaabonos),2);?></td></tr>
                                </table>
                            </div>
                        </div>
                        <div clas="col-md-2">
                            <form action="../controller/Reportes/ReporteFinMesGasolina.php" method="POST">
                                <input type="hidden" name="zone" value="<?php echo $zona; ?>">
                                <input type="hidden" name="saldo" value="<?php echo $saldo_total ?>">
                                <input type="hidden" name="otorgados" value="<?php echo $totalotorgados ?>">
                                <input type="hidden" name="abonos" value="<?php echo $sumaabonos ?>">
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
                                    <th> Domicilio </th>
                                    <th> Colonia </th>
                                    <th> Número factura </th>
                                    <th> Folio Fiscal </th>
                                    <th> Precio litro </th>
                                    <th> Litros </th>     
                                    <th> Importe </th>
                                    <th> Importe pagado</th>
                                    <th> Vencimiento </th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($otorgados as $datos){
                                    $total=$total + $datos["importe"];
                                    $totalp=$totalp + $datos["importe_pagado"];
                                    $interval=dateDiff($hoy, $datos["fecha"]);

                                    if($interval>=10  && $interval<=20){
                                        echo "<td style= background-color:#FF8000; color:#F7EC4A;><b><font color='black'>".$datos["fecha"]."</b></td>";
                                    }
                                    
                                    else if($interval>=20){
                                        echo "<td style= background-color:#FA5858; color:#F7EC4A;><b><font color='white'>".$datos["fecha"]."</b></td>";
                                    }
                                    else {
                                        echo "<td style= background-color:#3ADF00; color:#F7EC4A;><b><font color='black'>".$datos["fecha"]."</b></td>";
                                    }
                                    echo "
                                        <td> ".$datos["nombre"]."</td>
                                        <td> ".$datos["domicilio"]."</td>
                                        <td> ".$datos["colonia"]."</td>
                                        <td> ".$datos["num_factura"]."</td>
                                        <td> ".$datos["folio_fiscal"]."</td>
                                        <td> $".$datos["precio_litro"]."</td>
                                        <td> ".$datos["litros"]."</td>
                                        <td> $".$datos["importe"]."</td>
                                        <td> $".$datos["importe_pagado"]."</td>
                                        <td> ".$datos["fecha_vencimiento"]."</td>                            
                                        </tr>";
                                }
                            ?>
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

</script>