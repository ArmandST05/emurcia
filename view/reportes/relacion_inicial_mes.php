<?php
$modelReporte = new ModelReporte();
$zona = ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") ? $_POST["zona"] : $_SESSION["zonaId"];
$mes = $_POST["mes"];
$anio = $_POST["anio"];
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

$mes = $mes - 1;
if ($mes == 0) {
    $mes = 12;
    $anio = $anio - 1;
}
$sumacreditosrecuperados = $modelReporte->obtenersumarecuperados2($zona, $mes, $anio);
$sumacreditosotorgados = $modelReporte->obtenersumaotorgados2($zona, $mes, $anio);
$sumaabonos = $modelReporte->obtenersumaabonosmespasado2($zona, $mes, $anio);
$otorgados = $modelReporte->obtenercreditosotorgadosmespasado2($zona, $mes, $anio);
$recuperados = $modelReporte->obtenercreditosrecuperadosmespasado2($zona, $mes, $anio);
$abonos = $modelReporte->obtenerabonosmespasado2($zona, $mes, $anio);

$recuperado = $sumaabonos + $sumacreditosrecuperados;
$saldototal = $sumacreditosotorgados - $recuperado;

$comercial = $modelReporte->obtenerid_cliente();
$comercial_rec = $modelReporte->obtenerid_cliente();
$recupera = $modelReporte->obtenercredrecmespasado2gas($zona, $mes, $anio);

$cantidad_otorgada2 = 0;
$cantidad_otorgada1 = 0;
$cantidad_otorgada = 0;
$total_abono = 0;
?>

<style type="text/css">
    input {
        text-align: right;
    }

    td {
        text-align: right;
    }
</style>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div class="inline-block">
        <a href="#"><i class="fas fa-home fa-sm"></i></a> /
        <a href="index.php?action=creditos/index.php">Créditos Gas</a> /
        <a href="index.php?action=creditos/generar_reportes.php">Reportes</a> /
        <a href="#">Relación Inicial al Mes</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Relación Inicial al Mes <br><?php echo $zona ?></h1>
</div>
<!-- Content Row -->
<div id="dvData">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados pendientes por recuperar</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="info">
                                <table>
                                    <tr><strong><label id='ot'></label></strong></tr><br>
                                    <tr><strong><label id='abo1'></label></strong></tr><br>
                                    <tr><strong><label id='saldo1'></label></strong></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <table id="tablaOtorgados" class="table table-sm table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> Nombre cliente </th>
                                <th> Domicilio </th>
                                <th> Nombre Comercial </th>
                                <th> No. factura </th>
                                <th> Folio fiscal </th>
                                <th> Precio litro </th>
                                <th> Litros </th>
                                <th> Importe </th>
                                <th> Importe pagado </th>
                                <th> Descuento </th>
                                <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($otorgados as $datos) {
                                if ($datos["status"] == 1) {
                                    //$cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"];    
                                    foreach ($recuperados as $datos1) {
                                        if ($datos["num_factura"] == $datos1["num_factura"]) {

                                            $interval = dateDiff($hoy, $datos["fecha"]);

                                            if ($interval >= 10  && $interval <= 20) {
                                                echo "<td style= background-color:#FF8000; color:#F7EC4A;><b><font color='black'>" . $datos["fecha"] . "</b></td>";
                                                $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"];
                                            } else if ($interval >= 20) {
                                                echo "<td style= background-color:#FA5858; color:#F7EC4A;><b><font color='white'>" . $datos["fecha"] . "</b></td>";
                                                $cantidad_otorgada2 = $cantidad_otorgada2 + $datos["importe"];
                                            } else {
                                                echo "<td style= background-color:#3ADF00; color:#F7EC4A;><b><font color='black'>" . $datos["fecha"] . "</b></td>";
                                                $cantidad_otorgada3 = $cantidad_otorgada3 + $datos["importe"];
                                            }


                                            echo "<td> " . $datos["nombre"] . "</td>
                                    <td> " . $datos["domicilio"] . "</td>";
                                            $idclientecredito = $datos["id_cliente"];
                                            $comercial = $modelReporte->obtenerid_clientecomercial($idclientecredito);
                                            echo "<td>";
                                            if (count($comercial) == 0) {
                                                //echo "vacio";
                                            } else {
                                                foreach ($comercial as $datos3) {
                                                    if (strcmp($datos1["id_cliente"], $datos3["num_cliente"]) == 0) {

                                                        echo $datos3["nombre_comercial"];
                                                    } else {
                                                        //echo "Eliminado por Administrador1";
                                                    }
                                                }
                                            }

                                            echo "</td><td> " . $datos["num_factura"] . "</td>
                                    <td> " . $datos["folio_fiscal"] . "</td>
                                    <td align='right'> $" . $datos["precio_litro"] . "</td>
                                    <td align='right'> " . $datos["litros"] . "</td>
                                    <td align='right'> $" . $datos["importe"] . "</td>
                                    <td align='right'> ";
                                            foreach ($abonos as $key) {
                                                if (strcmp($datos1["num_factura"], $key["nota"]) == 0) {
                                                    echo $key["suma_cantidad"];
                                                    $total_abono = $total_abono + $key["suma_cantidad_to"];;
                                                }
                                            }
                                            echo "</td>
                                    <td>" . $datos["descuento"] . "</td>
                                    <td>" . $datos["fecha_vencimiento"] . "</td>                            
                                    </tr>";
                                        } else {
                                            //echo "me valio mad";
                                        }
                                    }
                                } else {
                                    $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"];
                                    $cantidad_otorgada = $cantidad_otorgada + $datos["importe"];
                                    echo "<tr>";
                                    $interval = dateDiff($hoy, $datos["fecha"]);

                                    if ($interval >= 10  && $interval <= 20) {
                                        echo "<td style= background-color:#FF8000; color:#F7EC4A;><b><font color='black'>" . $datos["fecha"] . "</b></td>";
                                    } else if ($interval >= 20) {
                                        echo "<td style= background-color:#FA5858; color:#F7EC4A;><b><font color='white'>" . $datos["fecha"] . "</b></td>";
                                    } else {
                                        echo "<td style= background-color:#3ADF00; color:#F7EC4A;><b><font color='black'>" . $datos["fecha"] . "</b></td>";
                                    }

                                    echo "<td> " . $datos["nombre"] . "</td>
                            <td> " . $datos["domicilio"] . "</td>";
                                    $idclientecredito = $datos["id_cliente"];
                                    $comercial = $modelReporte->obtenerid_clientecomercial($idclientecredito);
                                    echo "<td>";
                                    if (count($comercial) == 0) {
                                        //echo count($comercial);
                                    } else {
                                        foreach ($comercial as $datos3) {
                                            if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {

                                                echo $datos3["nombre_comercial"];

                                                //$cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                            } else {
                                                // echo "Eliminado por Administrador2";
                                            }
                                        }
                                    }

                                    echo "</td><td> " . $datos["num_factura"] . "</td>
                            <td> " . $datos["folio_fiscal"] . "</td>
                            <td align='right'> $" . $datos["precio_litro"] . "</td>
                            <td align='right'> " . $datos["litros"] . "</td>
                            <td align='right'> $" . $datos["importe"] . "</td>
                            <td align='right'> ";
                                    foreach ($abonos as $key) {
                                        if (strcmp($datos["num_factura"], $key["nota"]) == 0) {
                                            //if($datos["num_factura"] == $key["nota"]){
                                            echo $key["suma_cantidad"];
                                            //echo $key["nota"];
                                            $total_abono = $total_abono + $key["suma_cantidad_to"];
                                        }
                                    }
                                    echo "</td>
                            <td>" . $datos["descuento"] . "</td>
                            <td> " . $datos["fecha_vencimiento"] . "</td>                            
                            </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Créditos recuperados</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="tablaRecuperados" class="table table-sm table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> Nombre cliente </th>
                                <th> Domicilio </th>
                                <th> Nombre Comercial </th>
                                <th> No. factura </th>
                                <th> Folio fiscal </th>
                                <th> Precio litro </th>
                                <th> Litros </th>
                                <th> Importe </th>
                                <th> Descuento </th>
                                <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($recupera as $datos) {
                                echo "<tr>
                        <td> " . $datos["fecha"] . "</td>
                        <td> " . $datos["nombre"] . "</td>
                        <td> " . $datos["domicilio"] . "</td>";
                                $idclientecredito = $datos["id_cliente"];
                                $comercial = $modelReporte->obtenerid_clientecomercial($idclientecredito);
                                echo "<td>";
                                if (count($comercial) == 0) {
                                    //echo count($comercial);
                                } else {
                                    foreach ($comercial as $datos3) {
                                        if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {

                                            echo $datos3["nombre_comercial"];

                                            //$cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                        } else {
                                            // echo "Eliminado por Administrador2";
                                        }
                                    }
                                }

                                echo "</td><td> " . $datos["num_factura"] . "</td>
                                    <td> " . $datos["folio_fiscal"] . "</td>
                                    <td align='right'> $" . $datos["precio_litro"] . "</td>
                                    <td align='right'> " . $datos["litros"] . "</td>
                                    <td align='right'> $" . $datos["importe"] . "</td>
                                    <td>" . $datos["descuento"] . "</td>
                                    <td>" . $datos["fecha_vencimiento"] . "</td>
 
                                               
                        </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="info">
    <table>
        <table>
            <tr>
                <td></td>
                <td><input type="hidden" id='' value='<?php echo  number_format(($total_abono), 2); ?>' readonly size="10"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="hidden" id='saldo' value='<?php echo  number_format((($cantidad_otorgada1 + $cantidad_otorgada2 + $cantidad_otorgada3) - ($total_abono)), 2); ?>' readonly size="10"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="hidden" id='otorg' value='<?php echo  number_format(($cantidad_otorgada1 + $cantidad_otorgada2 + $cantidad_otorgada3), 2); ?>' readonly size="10"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="hidden" value='<?php echo  number_format(($sumacreditosrecuperados), 2); ?>' readonly size="10"></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="hidden" id='abo' value='<?php echo  number_format(($total_abono), 2); ?>' readonly size="10"></td>
            </tr>
        </table>
</div>

<script type="text/JavaScript">
    $(document).ready(function () {
        agregarTotales();
    });
    $("#btnExport").click(function (e) {
        $("#datosexcel").btechco_excelexport({
                containerid: "datosexcel"
                , datatype: $datatype.Table
                , filename: 'creditosmes'
        });
    });

    $('#tablaOtorgados').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

    $('#tablaRecuperados').DataTable({
      "pageLength": 25,
      "language": {
        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
      }
    });

    function agregarTotales()
    {
        $("#ot").text("Otorgado pendiente por recuperar: $" + $("#otorg").val());
        $("#abo1").text("\nRecuperados parciales (abonos): $" + $("#abo").val());
        $("#saldo1").text(" Saldo: $" + $("#saldo").val());
    } 
</script>