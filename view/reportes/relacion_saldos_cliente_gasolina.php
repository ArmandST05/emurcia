<?php
$modelReporte = new ModelReporte();
$id = $_POST["cliente"];

$creditos_otor = $modelReporte->obtenerreportesstorgadosgasolina($id);
$creditos_rec = $modelReporte->obtenerreportesrecuperadosgasolina($id);
$abonos= $modelReporte->obtenerreportesabonos_cli($id);
$suma_otor=$modelReporte->suma_otorgadosgasolina($id);
$suma_rec=$modelReporte->suma_recuperadosgasolina($id);
$suma_abo=$modelReporte->totalAbonosCliente($id);
$cliente=$modelReporte->datos_clientes($id);

$total_rec=$suma_abo + $suma_rec;
$saldo= $suma_otor - $total_rec;

foreach ($cliente as $datos) {
    $nombre = $datos["nombre_cliente"];
    $zona = $datos["zona_id"];
    $id = $datos["num_cliente"];
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
        <a href="#">Relación de Saldos</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Saldos de <?php echo $nombre ?></h1>
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
                                    <tr><td><strong>Total otorgados: $<?php echo  number_format(($suma_otor),2);?></td></tr>
                                    <tr><td><strong>Total recuperados: $<?php echo  number_format(($total_rec),2);?></td></tr>
                                    <tr><td><strong>Saldo: $<?php echo  number_format(($saldo),2);?></td></tr>
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
                                <th> Fecha </th>
                                <th> Nombre cliente </th>
                                <th> Domicilio </th>
                                <th> Colonia </th>
                                <th> Número factura </th>
                                <th> Precio litro </th>
                                <th> Litros </th>     
                                <th> Importe </th>
                                <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($creditos_otor as $datos){
                            echo "<tr>
                                    <td> ".$datos["fecha"]."</td>
                                    <td> ".$datos["nombre"]."</td>
                                    <td> ".$datos["domicilio"]."</td>
                                    <td> ".$datos["colonia"]."</td>
                                    <td> ".$datos["num_factura"]."</td>
                                    <td> $".$datos["precio_litro"]."</td>
                                    <td> ".$datos["litros"]."</td>
                                    <td> $".$datos["importe"]."</td>
                                    <td> ".$datos["fecha_vencimiento"]."</td>                            
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos recuperados</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> Nombre cliente </th>
                                <th> Domicilio </th>
                                <th> Colonia </th>
                                <th> Número factura </th>
                                <th> Precio litro </th>
                                <th> Litros </th>     
                                <th> Importe </th>
                                <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($creditos_rec as $datos){
                                echo "<tr>
                                    <td> ".$datos["fecha"]."</td>
                                    <td> ".$datos["nombre"]."</td>
                                    <td> ".$datos["domicilio"]."</td>
                                    <td> ".$datos["colonia"]."</td>
                                    <td> ".$datos["num_factura"]."</td>
                                    <td> $".$datos["precio_litro"]."</td>
                                    <td> ".$datos["litros"]."</td>
                                    <td> $".$datos["importe"]."</td>
                                    <td> ".$datos["fecha_vencimiento"]."</td>                            
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos abonados</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th> Fecha </th>
                                <th> ID cliente </th>
                                <th> Nombre cliente </th>
                                <th> Número factura </th>
                                <th> Importe </th>
                                <th> Zona </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($abonos as $datos){
                                echo "<tr>
                                    <td> ".$datos["fecha"]."</td>
                                    <td> ".$datos["cliente"]."</td>
                                    <td> ".$datos["nombre"]."</td>
                                    <td> ".$datos["nota"]."</td>
                                    <td> $".$datos["cantidad"]."</td>
                                    <td> ".$datos["zona_id"]."</td>                            
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
                 , filename: 'saldodeclientesgasolina'
            });
        });
    });
</script>