<?php
$modelZona = new ModelZona();
$modelVenta = new ModelVenta();
$modelProducto = new ModelProducto();
$zonaUsuario = $_SESSION['zona'];
$zonaId = $_SESSION['zona'];
$usuario = $_SESSION["user"];

//Búsqueda de datos
$anio = date("Y");
$fechaInicial = (isset($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = (isset($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-d");
$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerZonasGas();
} elseif ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : $zonas[0]["idzona"];
}  else {
  $zonaId = $_SESSION['zonaId'];
}

//Ventas de rutas de lts
$rutasVenta = $modelVenta->rutasVentasLtsEntreFechas($zonaId, $fechaInicial, $fechaFinal);
//else $rutasVenta = $modelVenta->rutasVentasProductoEntreFechas($zonaId, $productoId, $fechaInicial, $fechaFinal);

$productos = $modelProducto->index();

$totalGralLitros = 0;
$totalGralCilindros = 0;
$totalGralTeoricoLts = 0;
$totalGralRealLts = 0;
$totalGralDiferenciaLts = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Ventas Gas</a> /
    <a href="#">Reporte F</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reporte de Ventas F</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Buscar</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body" name="otra" id="otra">
        <form action='index.php' method='GET'>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mv" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona">
                    <option value="0" disabled selected>Selecciona una</option>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo $dataZona["nombre"] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <!--
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Producto:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="producto" id="producto">
                  <option value="0">Todos</option>
                  <?php foreach ($productos as $producto) : ?>
                    <option value="<?php echo $producto['idproducto'] ?>;" <?php echo ($producto['idproducto'] == $productoId) ? 'selected' : '' ?>>
                      <?php echo $producto['nombre'] ?></option>
                  <?php endforeach; ?>
                  ?>
                </select>
              </div>
            </div>
          </div>
          -->
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" id="fechaInicial" name="fechaInicial" value="<?php echo $fechaInicial ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <input type='hidden' name='action' id='action' value="ventas/reporte_f.php" />
              <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Content Row -->
<div class="row">

  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Ventas</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th></th>
              <th>Kilos</th>
              <th>Litros</th>
              <th>Cilindros</th>
              <th>Real Lts</th>
              <th>Teórico L</th>
              <th>Diferencia Lts</th>
              <th>Porcentaje %</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rutasVenta as $claveRuta => $ruta) : ?>
              <tr data-tt-id="r<?php echo $claveRuta ?>">
                <td colspan="12"><b><?php echo $ruta["clave_ruta"] ?></b></td>
              </tr>
              <?php
              if ($productoId == 0) $fechas = $modelVenta->listaZonaRutaEntreFechas($zonaId, $ruta["idruta"], $fechaInicial, $fechaFinal);
              else $fechas = $modelVenta->listaZonaRutaProductoEntreFechas($zonaId, $ruta["idruta"], $productoId, $fechaInicial, $fechaFinal);
              $totalLitros = 0;
              $totalCilindros = 0;
              $totalTeoricoLts = 0;
              $totalRealLts = 0;
              $totalDiferenciaLts = 0;
              ?>
              <?php
              foreach ($fechas as $claveFecha => $venta) :
                //Venta de Lts en pipas
                if ($ruta["tipo_ruta_id"] == 1 || $ruta["tipo_ruta_id"] == 4 || $ruta["tipo_ruta_id"] == 5) {
                  $litros = $venta["cantidad"];
                  $kilos = ($litros * .54);
                  $cilindros = 0;

                  //CANTIDAD TEÓRICA VENDIDA = Lectura Final - Lectura Inicial
                  $teoricoLecturaLts = $venta["lectura_final"] - $venta["lectura_inicial"];

                  //CANTIDAD REAL VENDIDA = Porcentaje Final - Porcentaje Inicial
                  $porcentajeVenta = $venta["porcentaje_inicial"] - $venta["porcentaje_final"]; //Porcentaje vendido   
                  //Calcular en lts cuánto se vendió de acuerdo al porcentaje
                  $realPorcentajeLts = ($porcentajeVenta * $venta["ruta_capacidad"]) / 100;

                  $teoricoLecturaKg = $teoricoLecturaLts * .54;
                  $realPorcentajeKg = $realPorcentajeLts * .54;
                  $diferenciaLts = $teoricoLecturaLts - $realPorcentajeLts;
                  $diferenciaKg = $diferenciaLts * .54;
                } else if ($ruta["tipo_ruta_id"] == 2 || $ruta["tipo_ruta_id"] == 3) {
                  $kilos = $venta["cantidad"];
                  $litros = ($kilos / .54);
                  $cilindros = $venta["total_rubros_venta"];

                  $teoricoLecturaKg = 0;
                  $teoricoLecturaLts = 0;
                  $realPorcentajeKg = 0;
                  $realPorcentajeLts = 0;
                  $diferenciaKg = 0;
                  $diferenciaLts = 0;
                }

                $totalLitros += $litros;
                $totalCilindros += $cilindros;
                $totalTeoricoLts += $teoricoLecturaLts;
                $totalRealLts += $realPorcentajeLts;
                $totalDiferenciaLts += $diferenciaLts;
                $totalPorcentaje = ($totalDiferenciaLts / $totalRealLts)*100;
              ?>
                <!--Fechas -->
                <tr data-tt-id="f<?php echo $claveFecha ?>" data-tt-parent-id="r<?php echo $claveRuta ?>" class="text-right">
                  <td><?php echo $venta["fecha"]; ?></td>
                  <td><?php echo number_format($kilos, 2); ?></td>
                  <td><?php echo number_format($litros, 2); ?></td>
                  <td><?php echo number_format($cilindros, 2) ?></td>
                  <td><?php echo number_format($teoricoLecturaLts, 2); ?></td>
                  <td><?php echo number_format($realPorcentajeLts, 2); ?></td>
                  <td class="<?php echo ($diferenciaLts < 0) ? 'text-danger' : ''; ?>"><b><?php echo number_format($diferenciaLts, 2); ?></b></td>
                  <td><?php echo number_format($totalPorcentaje);?> %</td>
                </tr>
                <!--Fechas -->
              <?php endforeach; ?>
              <!--Totales ruta -->
              <tr class="bg-light text-right">
                <td></td>
                <td><b><?php echo number_format(($totalLitros * .54), 2) ?></b></td>
                <td><b><?php echo number_format($totalLitros, 2); ?></b></td>
                <td><b><?php echo number_format($totalCilindros, 2) ?></b></td>

                <!--<td><b><?php echo number_format(($totalTeoricoLts * .54), 2) ?></b></td>-->
                <td><b><?php echo number_format($totalTeoricoLts, 2) ?></b></td>

                <!--<td><b><?php echo number_format(($totalRealLts * .54), 2) ?></b></td>-->
                <td><b><?php echo number_format($totalRealLts, 2) ?></b></td>
                <!--<td><b><?php echo number_format(($totalDiferenciaLts * .54), 2) ?></b></td>-->
                <td class="<?php echo ($totalDiferenciaLts < 0) ? 'text-danger' : ''; ?>"><b><?php echo number_format($totalDiferenciaLts, 2) ?></b></td>
              </tr>
              <!--Totales ruta -->
            <?php
              $totalGralLitros += $totalLitros;
              $totalGralCilindros += $totalCilindros;
              $totalGralTeoricoLts += $totalTeoricoLts;
              $totalGralRealLts += $totalRealLts;
              $totalGralDiferenciaLts += $totalDiferenciaLts;
            endforeach;
            ?>
            <tr>
              <th></th>
              <th>Kilos</th>
              <th>Litros</th>
              <th>Cilindros</th>
              <th>Teórico L</th>
              <th>Real Lts</th>
              <th>Diferencia Lts</th>
            </tr>
            <!--Totales zona -->
            <tr class="bg-light text-right">
              <td><b>TOTAL</b></td>
              <td><b><?php echo number_format(($totalGralLitros * .54), 2) ?></b></td>
              <td><b><?php echo number_format($totalGralLitros, 2) ?></b></td>
              <td><b><?php echo number_format($totalGralCilindros, 2); ?></b></td>

              <!--<td><b><?php echo number_format(($totalGralTeoricoLts * .54), 2) ?></b></td>-->
              <td><b><?php echo number_format($totalGralTeoricoLts, 2) ?></b></td>

              <!--<td><b><?php echo number_format(($totalGralRealLts * .54), 2) ?></b></td>-->
              <td><b><?php echo number_format($totalGralRealLts, 2) ?></b></td>
              <!--<td><b><?php echo number_format(($totalGralDiferenciaLts * .54), 2) ?></b></td>-->
              <td class="<?php echo ($totalGralDiferenciaLts < 0) ? 'text-danger' : ''; ?>"><b><?php echo number_format($totalGralDiferenciaLts, 2) ?></b></td>
            </tr>
            <!--Totales zona -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
  });

  $("#listaTabla").treetable({ 
    expandable: true 
  });

  $('#listaTabla').treetable('collapseAll');

  //Exportar a Excel
  $("#btnExport").click(function (e) {
    $('#listaTabla').treetable('expandAll');
    let zona = "<?php echo $zona ?>";
    let fechaInicial = "<?php echo $fechaInicial ?>";
    let fechaFinal = "<?php echo $fechaFinal ?>";

    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla"
      , datatype: $datatype.Table
      , filename: 'Ventas F-'+zona+'-De'+fechaInicial+'A'+fechaFinal
    });
  });

  /*$('#listaTabla').DataTable({
    pageLength: 100,
    ordering: false,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });*/
</script>