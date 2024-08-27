<?php
$modelCredito = new ModelCredito();
$modelCliente = new ModelCliente();
$idCliente = $_POST["cliente"];
$creditos = $modelCredito->obtenerCreditosGasolinaOtorgadosCliente('cliente',$idCliente);
$cliente = $modelCliente->buscarPorId($idCliente);
$cliente = reset($cliente);
$importe = 0;
$importe_pag = 0;
$saldo = 0;

foreach ($creditos as $credito) {
    $importe = $importe + $credito["importe"];
    $importe_pag = $importe_pag + $credito["importe_pagado"];
    $saldo = $importe - $importe_pag;
}
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
        <a href="index.php?action=creditosgasolina/index.php">Créditos Gasolina</a> /
        <a href="index.php?action=creditosgasolina/generar_reportes.php">Reportes</a> /
        <a href="#">Créditos Otorgados</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte Créditos Otorgados de <?php echo $cliente['nombre_cliente'] ?></h1>
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
                                <tr><strong><label ><?php echo "Total otorgados: $".$importe ?></label></strong></tr><br>
                        <tr><strong><label><?php echo "Abonos: $".$importe_pag ?></label></strong></tr><br>
                        <tr><strong><label><?php echo "Saldo: $".$saldo ?></label></strong></tr>
                                </table>
                            </div>
                        </div>
                        <div clas="col-md-2">
                            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
                        </div>
                    </div>
                    <table id="datosexcel" class="table table-bordered table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th> Fecha alta </th>
                                <th> Número factura </th>
                                <th> Folio fiscal </th>
                                <th> Nombre cliente </th>
                                <th> Litros </th>
                                <th> Precio </th>
                                <th> Importe </th>
                                <th> Importe abonado</th>
                                <th> Vencimiento </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($creditos as $datos) {
                                echo "<tr>
                                        <td> " . $datos["fecha"] . "</td>
                                        <td> " . $datos["num_factura"] . "</td>
                                        <td> " . $datos["folio_fiscal"] . "</td>
                                        <td> " . $datos["nombre"] . "</td>
                                        <td> " . $datos["litros"] . "</td>
                                        <td> $" . $datos["precio_litro"] . "</td>
                                        <td> $" . $datos["importe"] . "</td>
                                        <td> $" . $datos["importe_pagado"] . "</td>
                                        <td> " . $datos["fecha_vencimiento"] . "</td>
                                    </tr>";
                            }
                            ?>
                            <tr class="bg-light">
                                <th>Total</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="text-align:right">
                                  
                                </td>
                                <td style="text-align:right">
                                  
                                </td>
                                </td>
                                <td style="text-align:right">
                                    <strong>$<?php echo number_format(($importe), 2) ?></strong>
                                </td>
                                <td style="text-align:right">
                                    <strong>$<?php echo number_format(($importe_pag), 2) ?></strong>
                                </td>
                                <td></td>
                            </tr>
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
                , filename: 'creditosclientesgasolina'
            });
        });
    });
</script>