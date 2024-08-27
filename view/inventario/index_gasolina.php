<?php
$modelZona = new ModelZona();
$modelVenta = new ModelVenta();
$modelInventario = new ModelInventario();
$meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
$fechaActual = date("Y-m-d");
$fechaInicial = (!empty($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-01");
$fechaFinal = (!empty($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-31");

$mes = (!empty($_GET["mes"])) ? $_GET["mes"] : date("n");
$anio = (!empty($_GET["anio"])) ? $_GET["anio"] : date("Y");
$anioActual = date("Y");

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerZonasGasolina();
} else {
  $zonaId = $_SESSION['zonaId'];
}
$inventarios = $modelInventario->obtenerReporteInventarioGasolinaFechas($fechaInicial, $fechaFinal, $mes, $anio, $zonaId);
$rutas = $modelInventario->obtenerInventarioInicialGasolinaZonaMesAnio($zonaId, $mes, $anio);
$totalKilos = 0;
$totalLitros = 0;

if ($_SESSION["tipoUsuario"] == "u" && $_SESSION["tipoZona"] != 2) {
  echo "<script> 
          alert('Tu zona no vende GASOLINA... Redireccionando a Inventario Gas');
          window.location.href = 'index.php?action=inventario/index_rutas.php';
        </script>";
}
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Inventario Gasolina</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Inventario Gasolina</h1>
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
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona">
                    <option selected disabled>Seleciona opción</option>
                    <?php foreach ($zonas as $dataZona) : ?>
                      <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($dataZona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Fecha:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mes" id="mes">
                  <?php for ($j = 1; $j <= 12; $j++) : ?>
                    <option value="<?php echo $j; ?>" <?php echo ($mes == $j) ? "selected" : ""; ?>>
                      <?php echo $meses[$j] ?>
                    </option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class=" col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anio" id="anio">
                  <?php for ($k = $anioActual; $k >= 2010; $k--) : ?>
                    <option value="<?php echo $k; ?>" <?php echo ($anioActual == $k) ? "selected" : ""; ?>>
                      <?php echo $k ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <input type='hidden' name='action' id='action' value="inventario/index_gasolina.php" />
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
        <h6 class="m-0 font-weight-bold text-primary">Inventario Inicial <?php echo $meses[$mes] ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php foreach ($rutas as $ruta) : ?>
          <form action="../controller/Inventario/InsertarInventarioInicial.php" method="POST">
            <div class="row">
              <div class="col-md-3">
                <?php echo $ruta["ruta_nombre"]; ?>
              </div>
              <div class="col-md-2">
                <input type="number" step=".01" min=".01" max="<?php echo $ruta["ruta_capacidad"]; ?>" class="form-control form-control-sm" name="cantidad" value="<?php echo $ruta["inventario_inicial"]; ?>" <?php echo ($ruta["inventario_inicial"] || $_SESSION["tipoUsuario"] == "uc") ? "readonly" : "" ?> required></input>
              </div>
              <div class="col-md-2">
                <?php if (!$ruta['inventario_inicial'] && ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u")) : ?>
                  <input type="hidden" name="rutaId" id="rutaId" value="<?php echo $ruta['ruta_id'] ?>" required>
                  <input type="hidden" name="zonaId" id="zonaId" value="<?php echo $zonaId ?>" required>
                  <input type="hidden" name="productoId" id="productoId" value="<?php echo $ruta['producto_id'] ?>" required>
                  <button class='btn btn-sm btn-primary' type='submit' data-toggle="tooltip" title="Guardar"><i class='fas fa-check fa-sm'></i></button>
                <?php endif; ?>
              </div>
            </div>
          </form>
        <?php endforeach; ?>
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
        <h6 class="m-0 font-weight-bold text-primary">Inventario</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <div class="row">
          <div class="alert alert-warning col-md-12" role="alert">
            Para ver los detalles de las ventas, haz clic en la flecha junto al nombre del tanque.
          </div>
        </div>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Tanque</th>
              <th>Producto</th>
              <th>Capacidad</th>
              <th>Inventario Mínimo</th>
              <th>Inventario Físico</th>
              <th>Inventario Contable</th>
              <th colspan="2">Ventas Total Lts</th>
              <th>Promedio Ventas Día</th>
              <th>Inventario Restante Días</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inventarios as $claveRuta => $ruta) :
            //El total de ventas general y la cantidad de ventas general sirven para calcular el promedio de ventas por día y el inventario para días de restante aunque en el mes actual todavía no se registren ventas
            //El total de ventas por mes se muestra en la columna Ventas Total Lts, ya que la información de las ventas e inventario es mensual
              $ventasFechas = $modelVenta->listaZonaRutaProductoEntreFechas($zonaId, $ruta['ruta_id'], $ruta['producto_id'], $fechaInicial, $fechaFinal);
              $totalLitrosFecha = 0;
              $totalKilosFecha = 0;
              if ($ruta["cantidad_ventas_general"] > 0) $promedioVtasDia = $ruta["total_ventas_general"] / $ruta["cantidad_ventas_general"];
              else $promedioVtasDia = 0;

              $inventarioContable = (floatval($ruta['inventario_inicial']) + floatval($ruta['total_compras'])) - floatval($ruta["total_ventas_mes"]);
              //No se debe de vender cuando el inventario contable sea menor al inventario mínimo de venta
              if($inventarioContable > 0) $inventarioVenta = $inventarioContable - floatval($ruta['ruta_inventario_minimo']);
              else $inventarioVenta = 0;

              if ($promedioVtasDia && $inventarioVenta) $diasVenta = round($inventarioVenta / $promedioVtasDia, 0, PHP_ROUND_HALF_DOWN);
              else $diasVenta = 0;
            ?>
              <tr data-tt-id="r<?php echo $claveRuta ?>" class="text-right 
                <?php if ($diasVenta <= 3) echo "bg-danger text-white";
                elseif ($diasVenta == 5) echo "bg-warning text-white";
                else echo "bg-light"
                ?>">
                <td><b><?php echo $ruta["ruta_nombre"]; ?></b></td>
                <td><?php echo $ruta["producto_nombre"]; ?></td>
                <td><?php echo $ruta["ruta_capacidad"]; ?></td>
                <td><?php echo number_format($ruta["ruta_inventario_minimo"], 2); ?></td>
                <td><?php echo number_format($ruta['inventario_inicial'], 2) ?></td>
                <td><?php echo number_format($inventarioContable, 2) ?></td>
                <td colspan="2" class="text-center"><?php echo number_format($ruta["total_ventas_mes"], 2); ?></td>
                <td><?php echo number_format($promedioVtasDia, 2); ?></td>
                <td><?php echo $diasVenta; ?></td>
              </tr>
              <?php foreach ($ventasFechas as $claveVenta => $venta) : ?>
                <tr data-tt-id="v<?php echo $claveVenta ?>" data-tt-parent-id="r<?php echo $claveRuta ?>">
                  <td colspan="6"></td>
                  <td><?php echo $venta["fecha"]; ?></td>
                  <td><?php echo number_format($venta["cantidad"],2); ?></td>
                  <td colspan="2"></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
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

  $("#modalEditarInventario").modal({
    show: false,
    backdrop: 'static',
    keyboard: false
  });

  $("#btnExport").click(function (e) {
    $('#listaTabla').treetable('expandAll');
    $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'inventarioZona'
    });
  });
</script>