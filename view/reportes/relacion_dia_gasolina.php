<?php
$modelReporte = new ModelReporte();
$dia = $_POST["diaini"];
$mes = $_POST["mesini"];
$anio = $_POST["anioini"];
$zona = ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") ? $_POST["zona"] : $_SESSION["zonaId"];

$sumacreditosrecuperados = $modelReporte->obtenersumarecuperadosdiaespecificogasolina($dia,$mes,$anio,$zona);
$sumacreditosotorgados = $modelReporte->obtenersumaotorgadosdiaespecificogasolina($dia,$mes,$anio,$zona);
$sumaabonos = $modelReporte->obtenersumaabonos($dia,$mes,$anio,$zona);

$otorgados_mag = $modelReporte->obtenercreditosotorgadosdiaespecificogasolina($dia,$mes,$anio,$zona);
$otorgados_pre = $modelReporte->obtenercreditosotorgadosdiaespecificogasolina_pre($dia,$mes,$anio,$zona);
$otorgados_die = $modelReporte->obtenercreditosotorgadosdiaespecificogasolina_die($dia,$mes,$anio,$zona);
$otorgados_acei = $modelReporte->obtenercreditosotorgadosdiaespecificogasolina_acei($dia,$mes,$anio,$zona);

$recuperados = $modelReporte->obtenercreditosrecuperadosdiaespecificogasolina($dia,$mes,$anio,$zona);
$abonos = $modelReporte->obtenercreditosabonados($dia,$mes,$anio,$zona);
$comercial=$modelReporte->obtenerid_cliente();

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
        <a href="#">Relación del día</a>
    </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Reporte de Relación del día <br><?php echo $zona ?></h1>
</div>
<!-- Content Row -->
<div id="dvData">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados magna</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="info">
                                <table>
                                    <tr>
                                        <td>Total otorgados: <?php echo  "<strong>"."$".number_format(($sumacreditosotorgados),2);?></td>
                                    </tr>
                                    <tr>
                                        <td>Total recuperados: <?php echo   "<strong>"."$".number_format(($sumaabonos),2);?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div clas="col-md-2">
                            <form action="../controller/Reportes/ReporteDiaEspecificogasolina.php" method="POST">
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
                                <th> Nom cliente </th>
                                <th> Domicilio </th>
                                <th> Nombre Comercial </th>
                                <th> Colonia </th>
                                <th> Num factura </th>
                                <th> Folio fiscal </th>
                                <th> Precio litro </th>
                                <th> Tipo </th>
                                <th> Litros </th>
                                <th> Subtotal </th>   
                                <th> Importe </th>
                                <th> IEPS </th>
                                <th> IVA </th>
                                <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $litros_general_mag=0; $importe_general_mag=0;
                            $iva_magna_full=0;
                            $ieps_magna_full=0;
                            $subtotal_magna_full=0;
               
                            foreach($otorgados_mag as $datos){
                                $litros_mag=$datos["litros"]; $importe_mag=$datos["importe"];
                                $litros_general_mag=$litros_general_mag+$litros_mag;
                                $importe_general_mag=$importe_general_mag+$importe_mag;

                                $ivaa_magna=$datos["IVA"];
                                $iva_magna_full=$iva_magna_full+$ivaa_magna;

                                $ieps_magna=$datos["ieps"];
                                $ieps_magna_full=$ieps_magna_full+$ieps_magna;

                                $sutotal_magna=$datos["subtotal"];
                                $subtotal_magna_full=$subtotal_magna_full+$sutotal_magna;

                                echo "<tr>
                                    <td> ".$datos["fecha"]."</td>
                                    <td> ".$datos["nombre"]."</td>
                                    <td> ".$datos["domicilio"]."</td>
                                    ";
                                            foreach ($comercial as $datos3) {
                                            if(strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0){
                                                echo "<td> ".$datos3["nombre_comercial"]."</td>";

                                            // $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                            }
                                            }
                                            echo "
                                    <td> ".$datos["colonia"]."</td>
                                    <td> ".$datos["num_factura"]."</td>
                                    <td> ".$datos["folio_fiscal"]."</td>
                                    <td> $".$datos["precio_litro"]."</td>
                                    <td> ".$datos["tipo_producto"]."</td>
                                    <td> ".$datos["litros"]."</td>
                                    <td> $".$datos["subtotal"]."</td>
                                    <td> $".$datos["importe"]."</td>
                                    <td> $".$datos["ieps"]."</td>
                                    <td> $".$datos["IVA"]."</td>
                                    <td> ".$datos["fecha_vencimiento"]."</td>                            
                                    </tr>";
                            }
                        ?>
                        <tr bgcolor="gray"><th>Total</th><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td>
                        </td><td style="text-align:right"><font color="white"><strong><?php echo number_format(($litros_general_mag),2) ?></strong></font></td>
                        <td><font color="white"><strong>$<?php echo number_format(($subtotal_magna_full),2) ?></strong></font></td>
                        <td style="text-align:right"><font color="white"><strong>$<?php echo number_format(($importe_general_mag),2) ?></strong></font></td>
                        <td><font color="white"><strong>$<?php echo number_format(($ieps_magna_full),2) ?></strong></font></td>
                        <td><font color="white"><strong>$<?php echo number_format(($iva_magna_full),2) ?></strong></font></td><td></td>
                        </tr>
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados premium</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                            <th> Fecha </th>
                   <th> Nom cliente </th>
                   <th> Domicilio </th>
                   <th> Nombre Comercial </th>
                   <th> Colonia </th>
                   <th> Num factura </th>
                   <th> Folio fiscal </th>
                   <th> Precio litro </th>
                   <th> Tipo </th>
                   <th> Litros </th>
                   <th> Subtotal </th>   
                   <th> Importe </th>
                   <th> IEPS </th>
                   <th> IVA </th>
                   <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                $litros_general_p=0; $importe_general_p=0;
                $iva_2_full=0;
                $ieps_2_full=0;
                $subtotal_2_full=0;

                    foreach($otorgados_pre as $datos){
                         $litros_p=$datos["litros"]; $importe_p=$datos["importe"];
                         $litros_general_p=$litros_general_p+$litros_p;
                         $importe_general_p=$importe_general_p+$importe_p;

                         $ivaa_2=$datos["IVA"];
                         $iva_2_full=$iva_2_full+$ivaa_2;

                         $ieps_2=$datos["ieps"];
                         $ieps_2_full=$ieps_2_full+$ieps_2;

                         $sutotal_2=$datos["subtotal"];
                         $subtotal_2_full=$subtotal_2_full+$sutotal_2;

                        echo "<tr>
                            <td> ".$datos["fecha"]."</td>
                            <td> ".$datos["nombre"]."</td>
                            <td> ".$datos["domicilio"]."</td>
                            ";
                                    foreach ($comercial as $datos3) {
                                     if(strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0){
                                        echo "<td> ".$datos3["nombre_comercial"]."</td>";

                                    // $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                     }
                                    }
                                    echo "
                            <td> ".$datos["colonia"]."</td>
                            <td> ".$datos["num_factura"]."</td>
                            <td> ".$datos["folio_fiscal"]."</td>
                            <td> $".$datos["precio_litro"]."</td>
                            <td> ".$datos["tipo_producto"]."</td>
                            <td> ".$datos["litros"]."</td>
                            <td> $".$datos["subtotal"]."</td>
                            <td> $".$datos["importe"]."</td>
                            <td> $".$datos["ieps"]."</td>
                            <td> $".$datos["IVA"]."</td>
                            <td> ".$datos["fecha_vencimiento"]."</td>                            
                            </tr>";
                    }
                ?>
                 <tr bgcolor="gray"><th>Total</th><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td>
                  </td><td style="text-align:right"><font color="white"><strong><?php echo number_format(($litros_general_p),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($subtotal_2_full),2) ?></strong></font></td>
                  <td style="text-align:right"><font color="white"><strong>$<?php echo number_format(($importe_general_p),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($ieps_2_full),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($iva_2_full),2) ?></strong></font></td><td></td>
                  </tr>
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados diesel</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                            <th> Fecha </th>
                   <th> Nom cliente </th>
                   <th> Domicilio </th>
                   <th> Nombre Comercial </th>
                   <th> Colonia </th>
                   <th> Num factura </th>
                   <th> Folio fiscal </th>
                   <th> Precio litro </th>
                   <th> Tipo </th>
                   <th> Litros </th>
                   <th> Subtotal </th>   
                   <th> Importe </th>
                   <th> IEPS </th>
                   <th> IVA </th>
                   <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                $litros_general_d=0; $importe_general_d=0;
                $iva_3_full=0;
                $ieps_3_full=0;
                $subtotal_3_full=0;

                    foreach($otorgados_die as $datos){
                         $litros_d=$datos["litros"]; $importe_d=$datos["importe"];
                         $litros_general_d=$litros_general_d+$litros_d;
                         $importe_general_d=$importe_general_d+$importe_d;

                         $ivaa_3=$datos["IVA"];
                         $iva_3_full=$iva_3_full+$ivaa_3;

                         $ieps_3=$datos["ieps"];
                         $ieps_3_full=$ieps_3_full+$ieps_3;

                         $sutotal_3=$datos["subtotal"];
                         $subtotal_3_full=$subtotal_3_full+$sutotal_3;

                        echo "<tr>
                            <td> ".$datos["fecha"]."</td>
                            <td> ".$datos["nombre"]."</td>
                            <td> ".$datos["domicilio"]."</td>
                            ";
                                    foreach ($comercial as $datos3) {
                                     if(strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0){
                                        echo "<td> ".$datos3["nombre_comercial"]."</td>";

                                    // $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                     }
                                    }
                                    echo "
                            <td> ".$datos["colonia"]."</td>
                            <td> ".$datos["num_factura"]."</td>
                            <td> ".$datos["folio_fiscal"]."</td>
                            <td> $".$datos["precio_litro"]."</td>
                            <td> ".$datos["tipo_producto"]."</td>
                            <td> ".$datos["litros"]."</td>
                            <td> $".$datos["subtotal"]."</td>
                            <td> $".$datos["importe"]."</td>
                            <td> $".$datos["ieps"]."</td>
                            <td> $".$datos["IVA"]."</td>
                            <td> ".$datos["fecha_vencimiento"]."</td>                            
                            </tr>";
                    }
                ?>
                 <tr bgcolor="gray"><th>Total</th><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td>
                  </td><td style="text-align:right"><font color="white"><strong><?php echo number_format(($litros_general_d),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($subtotal_3_full),2) ?></strong></font></td>
                  <td style="text-align:right"><font color="white"><strong>$<?php echo number_format(($importe_general_d),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($ieps_3_full),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($iva_3_full),2) ?></strong></font></td><td></td>
                 
            </tr>
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
                    <h6 class="m-0 font-weight-bold text-primary">Créditos otorgados aceites</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <table border="1" id="datosexcel" class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                            <th> Fecha </th>
                   <th> Nom cliente </th>
                   <th> Domicilio </th>
                   <th> Nombre Comercial </th>
                   <th> Colonia </th>
                   <th> Num factura </th>
                   <th> Folio fiscal </th>
                   <th> Precio litro </th>
                   <th> Tipo </th>
                   <th> Litros </th>
                   <th> Subtotal </th>   
                   <th> Importe </th>
                   <th> IEPS </th>
                   <th> IVA </th>
                   <th> Vencimiento </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                $importe_general_a=0; 
                $litros_general_a=0;
                $iva_4_full=0;
                $ieps_4_full=0;
                $subtotal_4_full=0;

                    foreach($otorgados_acei as $datos){
                         $importe_a=$datos["importe"]; 
                         $importe_general_a=$importe_general_a+$importe_a;

                         $litros_a=$datos["litros"];
                         $litros_general_a=$litros_general_a+$litros_a;

                         $ivaa_4=$datos["IVA"];
                         $iva_4_full=$iva_4_full+$ivaa_4;

                         $ieps_4=$datos["ieps"];
                         $ieps_4_full=$ieps_4_full+$ieps_4;

                         $sutotal_4=$datos["subtotal"];
                         $subtotal_4_full=$subtotal_4_full+$sutotal_4;

                        echo "<tr>
                            <td> ".$datos["fecha"]."</td>
                            <td> ".$datos["nombre"]."</td>
                            <td> ".$datos["domicilio"]."</td>
                            ";
                                    foreach ($comercial as $datos3) {
                                     if(strcmp($datos["id_cliente"], $datos3["num_cliente"]) == 0){
                                        echo "<td> ".$datos3["nombre_comercial"]."</td>";

                                    // $cantidad_otorgada1 = $cantidad_otorgada1 + $datos["importe"]; 
                                     }
                                    }
                                    echo "
                            <td> ".$datos["colonia"]."</td>
                            <td> ".$datos["num_factura"]."</td>
                            <td> ".$datos["folio_fiscal"]."</td>
                            <td> $".$datos["precio_litro"]."</td>
                            <td> ".$datos["tipo_producto"]."</td>
                            <td> ".$datos["litros"]."</td>
                            <td> $".$datos["subtotal"]."</td>
                            <td> $".$datos["importe"]."</td>
                            <td> $".$datos["ieps"]."</td>
                            <td> $".$datos["IVA"]."</td>
                            <td> ".$datos["fecha_vencimiento"]."</td>                            
                            </tr>";
                    }
                ?>
                 <tr bgcolor="gray"><th>Total</th><td></td><td></td><td></td><td></td><td><td></td><td></td><td></td>
                  </td><td style="text-align:right"><font color="white"><strong><?php echo number_format(($litros_general_a),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($subtotal_4_full),2) ?></strong></font></td>
                  <td style="text-align:right"><font color="white"><strong>$<?php echo number_format(($importe_general_a),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($ieps_4_full),2) ?></strong></font></td>
                  <td><font color="white"><strong>$<?php echo number_format(($iva_4_full),2) ?></strong></font></td><td></td>
                 </tr>
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
                                <th> Numero factura </th>    
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
                 , filename: 'creditosdiagasolina'
            });
        });
    });
</script>