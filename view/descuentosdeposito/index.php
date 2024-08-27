<?php
$modelZona = new ModelZona();
$modelVenta = new ModelVenta();
$modelDescuentoDeposito = new ModelDescuentoDeposito();
$fechaInicial = (!empty($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = (!empty($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-d");

if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";

  if ($zonaId) {
    $zonaNombre = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zonaNombre["nombre"];
  }

  $zonas = $modelZona->obtenerZonasTodas();
} elseif ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);

  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : $zonas[0]["idzona"];
  if ($zonaId) {
    $zonaNombre = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zonaNombre["nombre"];
  }
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

$fechasVentasContado = $modelVenta->obtenerFechasVentasContadoZona($zonaId, $fechaInicial, $fechaFinal);

$totalGralDescuentos = 0;
$totalGralVentasContado = 0;
$totalGralEfectivo = 0;
$totalGralPE = 0;
$totalGralVR = 0;
$totalGralGT = 0;
$totalGralCH = 0;
$totalGralO = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Detallado Depósitos</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Relación Depósitos de Ventas</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "mv") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=descuentosdeposito/nuevo.php<?php echo ($_SESSION["tipoUsuario"] == 'mv') ? '&zonaId=' . $zonaId : '' ?>">Nuevo</a>
  <?php endif; ?>
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
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "mv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
                    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") : ?>
                      <option selected disabled>Seleciona opción</option>
                    <?php endif; ?>
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
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" id="fechaFinal" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="descuentosdeposito/index.php" />
          <div class="row">
            <div clas="col-md-1 offset-md-10">
              <input class="btn btn-primary btn-sm" type='submit' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Card -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Relación Depósitos de Ventas</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <div class="row">
          <table class="table table-bordered table-sm table-responsive" id="listaTabla" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Ventas</th>
                <th>PE</th><!-- Pago Electrónico -->
                <th>VR</th><!-- Vale de Retiro (Váucher) -->
                <th>Descripción VR</th><!-- Descripción Vale de Retiro (Váucher) -->
                <th>GT</th><!-- Gastos -->
                <th>CH</th><!-- Cheque -->
                <th>T (Transferencias)</th><!-- Otras Salidas -->
                <th>Total Descuentos</th><!-- Total Descuentos -->
                <th>Efectivo</th><!-- Efectivo -->
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($fechasVentasContado as $fechaVentaContado) :
                $totalPE = 0;
                $totalVR = 0;
                $totalGT = 0;
                $totalCH = 0;
                $totalO = 0;
                $totalEfectivo = 0;
                $totalDescuentosFecha = 0;
              ?>
                <tr class="bg-light text-right" data-tt-id="<?php echo $fechaVentaContado["fecha"] ?>">
                  <td><b><?php echo $fechaVentaContado["fecha"] ?></b></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <?php
                if ($fechaVentaContado["cantidad_descuentos_deposito"] > 0) :
                  $descuentosDespositos = $modelDescuentoDeposito->listaZonaFecha($zonaId, $fechaVentaContado["fecha"]);
                  foreach ($descuentosDespositos as $claveDescuento => $descuento) :
                    $totalPE += $descuento["pago_electronico"];
                    $totalVR += $descuento["vale_retiro"];
                    $totalGT += $descuento["gastos"];
                    $totalCH += $descuento["cheque"];
                    $totalO += $descuento["otras_salidas"];
                    $totalDescuento = $descuento["pago_electronico"] +  $descuento["vale_retiro"] + $descuento["gastos"] + $descuento["cheque"] + $descuento["otras_salidas"];
                    $totalDescuentosFecha += $totalDescuento;
                ?>
                    <tr class="text-right" data-tt-id="<?php echo $claveDescuento ?>" data-tt-parent-id="<?php echo $fechaVentaContado["fecha"] ?>">
                      <td><?php echo $descuento["fecha"] ?></td>
                      <td></td>
                      <td>$<?php echo number_format($descuento["pago_electronico"], 2) ?></td>
                      <td>$<?php echo number_format($descuento["vale_retiro"], 2) ?></td>
                      <td class="text-justify"><?php echo $descuento["descripcion_vale_retiro"] ?></td>
                      <td>$<?php echo number_format($descuento["gastos"], 2) ?></td>
                      <td>$<?php echo number_format($descuento["cheque"], 2) ?></td>
                      <td>$<?php echo number_format($descuento["otras_salidas"], 2) ?></td>
                      <td>$<?php echo number_format($totalDescuento, 2) ?></td>
                      <td></td>
                      <td class="text-center">
                        <?php if ($_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "mv") : ?>
                          <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $descuento['iddescuentodeposito']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php
                  endforeach;
                  $totalEfectivo += ($fechaVentaContado["total_venta_contado"] - $totalDescuentosFecha);
                  //Sumatoria Totales Finales
                  $totalGralPE += $totalPE;
                  $totalGralVR += $totalVR;
                  $totalGralGT += $totalGT;
                  $totalGralCH += $totalCH;
                  $totalGralO += $totalO;
                  $totalGralDescuentos += $totalDescuentosFecha;
                  $totalGralEfectivo += $totalEfectivo;
                  ?>

                <?php else :
                  //Si no hay descuentos depósito ese día, de todos modos mostrar el total de las ventas.
                  $totalEfectivo += $fechaVentaContado["total_venta_contado"];
                  $totalGralEfectivo += $totalEfectivo;
                ?>
                  <tr data-tt-id="0" data-tt-parent-id="<?php echo $fechaVentaContado["fecha"] ?>">
                    <td></td>
                    <td colspan="10">No se registraron descuentos depósitos en esta fecha.</td>
                  </tr>
                <?php endif; ?>
                <tr class="text-right">
                  <td><b></b></td>
                  <td><b>$<?php echo number_format($fechaVentaContado["total_venta_contado"], 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalPE, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalVR, 2) ?></b></td>
                  <td></td>
                  <td><b>$<?php echo number_format($totalGT, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalCH, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalO, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalDescuentosFecha, 2) ?></b></td>
                  <td><b>$<?php echo number_format($totalEfectivo, 2) ?></b></td>
                  <td></td>
                </tr>
              <?php
                $totalGralVentasContado += $fechaVentaContado["total_venta_contado"];
              endforeach;
              ?>
              <tr class="text-right bg-light">
                <td><b>TOTAL</b></td>
                <td><b>$<?php echo number_format($totalGralVentasContado, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralPE, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralVR, 2) ?></b></td>
                <td></td>
                <td><b>$<?php echo number_format($totalGralGT, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralCH, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralO, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralDescuentos, 2) ?></b></td>
                <td><b>$<?php echo number_format($totalGralEfectivo, 2) ?></b></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {});

  tabla = $("#listaTabla").treetable({
    expandable: true
  });
  $('#listaTabla').treetable('expandAll');

  $("#btnExport").click(function(e) {
    let filename = 'ReporteRelacionDepositoVenta-' + "<?php echo $zonaNombre ?>" + " " + "<?php echo $fechaInicial ?>" + " A " + "<?php echo $fechaInicial ?>";
    $('#listaTabla').treetable('expandAll');
    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla",
      datatype: $datatype.Table,
      filename: filename
    });
  });

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el detalle?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/DescuentosDeposito/Eliminar.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Detalle eliminado");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar Detalle"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>