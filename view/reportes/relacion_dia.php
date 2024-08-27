<?php
$modelReporte = new ModelReporte();
$dia = $_POST["diaini"];
$mes = $_POST["mesini"];
$anio = $_POST["anioini"];
$zona = ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") ? $_POST["zona"] : $_SESSION["zonaId"];

$sumacreditosrecuperados = $modelReporte->obtenersumarecuperadosdiaespecifico($dia, $mes, $anio, $zona);
$sumacreditosotorgados = $modelReporte->obtenersumaotorgadosdiaespecifico($dia, $mes, $anio, $zona);
$sumaabonos = $modelReporte->obtenersumaabonos($dia, $mes, $anio, $zona);
$otorgados = $modelReporte->obtenercreditosotorgadosdiaespecifico($dia, $mes, $anio, $zona);
$recuperados = $modelReporte->obtenercreditosrecuperadosdiaespecifico($dia, $mes, $anio, $zona);
$abonos = $modelReporte->obtenercreditosabonados($dia, $mes, $anio, $zona);
$comercial = $modelReporte->obtenerid_cliente();
$folio_fiscal = $modelReporte->obtener_folio_fiscal($zona);

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
        <a href="#">Relación del día</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Relación del día<br><?php echo $zona ?></h1>
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
                                    <tr>
                                        <td>Total otorgados: <?php echo  "<strong>" . "$" . number_format(($sumacreditosotorgados), 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total recuperados: <?php echo   "<strong>" . "$" . number_format(($sumaabonos), 2); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div clas="col-md-2">
                            <form action="../controller/Reportes/ReporteDiaEspecifico.php" method="POST">
                                <input type="hidden" name="dia" value="<?php echo $dia; ?>">
                                <input type="hidden" name="mes" value="<?php echo $mes; ?>">
                                <input type="hidden" name="anio" value="<?php echo $anio; ?>">
                                <input type="hidden" name="zona" value="<?php echo $zona; ?>">
                                <button class="btn btn-sm btn-primary" type="submit"><i class="far fa-file-pdf"></i>Guardar PDF</button>
                            </form>
                            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
                        </div>
                    </div>
                    <table id="datosexcel" class="table table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> Nombre cliente </th>
                                <th> Domicilio </th>
                                <th> Nombre Comercial </th>
                                <th> Colonia </th>
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
                            foreach ($otorgados as $datos) {
                                echo "<tr>
                                    <td> " . $datos["fecha"] . "</td>
                                    <td> " . $datos["nombre"] . "</td>
                                    <td> " . $datos["domicilio"] . "</td>
                                    ";
                                foreach ($comercial as $datos3) {
                                    if (strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0) {
                                        echo "<td> " . $datos3["nombre_comercial"] . "</td>";

                                        // $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                    }
                                }
                                echo "
                                    <td> " . $datos["colonia"] . "</td>
                                    <td> " . $datos["num_factura"] . "</td>
                                    <td> " . $datos["folio_fiscal"] . "</td>
                                    <td> $" . $datos["precio_litro"] . "</td>
                                    <td> " . $datos["litros"] . "</td>
                                    <td> $" . $datos["importe"] . "</td>
                                    <td> $" . $datos["descuento"] . "</td>
                                    <td> " . $datos["fecha_vencimiento"] . "</td>                     
                                    </tr>";
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos recuperados totales y abonados</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> ID cliente </th>
                                <th> Nombre cliente </th>
                                <th> Nombre comercial </th>
                                <th> No. factura </th>
                                <th> Folio fiscal</th>
                                <th> Importe </th>
                                <th> Zona </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($abonos as $datos) {
                                echo "<tr>
                            <td> " . $datos["fecha"] . "</td>
                            <td> " . $datos["cliente"] . "</td>
                            <td> " . $datos["nombre"] . "</td>
                            <td> " . $datos["nombre_comercial"] . "</td>
                            <td> " . $datos["nota"] . "</td>
                            ";
                                foreach ($folio_fiscal as $datos2) {
                                    if (strcmp($datos["nota"], $datos2["num_factura"]) == 0) {
                                        echo "<td> " . $datos2["folio_fiscal"] . "</td>";
                                    }
                                }
                                echo "
                            <td> $" . $datos["cantidad"] . "</td>
                            <td> " . $datos["zona_id"] . "</td>                            
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

<script type="text/JavaScript">
    $(document).ready(function () {
        $("#btnExport").click(function (e) {
            $("#datosexcel").btechco_excelexport({
                  containerid: "datosexcel"
                 , datatype: $datatype.Table
                 , filename: 'creditosdia'
            });
        });
    });
</script>