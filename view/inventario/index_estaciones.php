<?php
date_default_timezone_set('America/Mexico_City');
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelInventario = new ModelInventario();
$modelCompania = new ModelCompania();
$modelVenta = new ModelVenta();
$modelAutoconsumo = new ModelAutoconsumo();
$modelTraspaso = new ModelTraspaso();

$fechaActual = date("Y-m-d");
$companias = $modelInventario-> obtenerCompaniasInventarioTeorico();
$companiaId = (isset($_GET["compania"])) ? $_GET["compania"] : null;

$mesBusqueda = (isset($_GET["mesInicial"])) ? $_GET["mesInicial"] : date("Y-m");
$fechaInicial = $mesBusqueda . "-01";
$fechaFinal = date("Y-m-t", strtotime($fechaInicial));
$fechaAnterior = date("Y-m-t", strtotime($fechaInicial . ' - 1 month'));

$fechaAnterior = date("Y-m-d", strtotime($fechaInicial . ' - 1 day'));
$diferenciaTotal = 0;
$totalComprasSucursales = 0;

if ($companiaId) {
    // Comprobamos si el usuario tiene el tipo adecuado
    if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
        $estacionesPorZona = $modelInventario->obtenerEstacionesPorCompania($companiaId);

        // Aquí puedes continuar con los cálculos adicionales que necesites
    } else {
        echo "No tienes permisos para acceder a este inventario.";
    }
} else {
    echo "No se ha seleccionado ninguna compañía.";
}


  $totalKilos = 0;
  $totalLitros = 0;

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Inventario Teórico Gas</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Inventario Teórico Gas</h1>
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
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv"): ?>
            <div class="row">
              <div class="col-lg-1 col-sm-6">
                <div class="form-group">
                  <label>Compañía:</label>
                </div>
              </div>
              <div class="col-lg-4 col-sm-6">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="compania" id="compania">
                    <option value="0" selected>Seleciona opción</option>
                    <?php foreach ($companias as $compania): ?>
                      <option value="<?php echo $compania['idcompania'] ?>" <?php echo ($companiaId == $compania['idcompania']) ? "selected" : "" ?>>
                        <?php echo $compania["nombre"] ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Mes:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <input class="form-control form-control-sm" type="month" id="mesInicial" name="mesInicial"
                    value="<?php echo $mesBusqueda ?>">
                </div>
              </div>
            </div>
          <?php endif; ?>
          <input type='hidden' name='action' id='action' value="inventario/index_estaciones.php" />
          <div class="row">
            <div class="col-md-2">
              <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEditarInventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="../controller/Inventario/InsertarInventario.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Inventario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              Inventario Actual
            </div>
            <div class="col-md-8">
              <input type="number" class="form-control form-control-sm" name="inventarioNuevo" id="inventarioNuevo"
                min="0" step=".01" value="0">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="rutaId" id="rutaId">
          <input type="hidden" name="productoId" id="productoId">
          <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if (isset($companiaId)): ?>
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
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar
                Excel</button>
            </div>
          </div>
          <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>VTA TOTAL</th>
              <th>ZONA</th>
              <th>Inv Inicial</th>
              <th>Traspasos a Estaciones</th> <!-- Nueva columna -->
              <th>C. Interno</th>
              <th>VTAS</th>
              <th>Contable</th>
              <th>Real</th>
              <th>Diferencia</th>
              <th>Porcentaje %</th>
            </tr>
          </thead>

          <tbody>
  <?php 
foreach ($estacionesPorZona as $zona):
    $ventasKg = $modelVenta->obtenerVentasKgZonaFechaEstaciones($fechaInicial, $fechaFinal);
    $totalVentaKg = $ventasKg[$zona["idruta"]] ?? 0;

    $inventarioAnteriorKg = $modelInventario->obtenerTotalInventarioGasKgZonaFechaEstaciones($zona["idruta"], $fechaAnterior);
    $totalInventarioAnteriorKg = $inventarioAnteriorKg["totalKgZona"];

    $inventarioKg = $modelInventario->obtenerTotalInventarioGasKgZonaFechaEstaciones($zona["idruta"], $fechaFinal);
    $totalKgInventarioActual = $inventarioKg["totalKgZona"]; // REAL = 100%
    $litrosPorEstacion = $inventarioKg["litrosPorEstacion"];



    $traspasosEstaciones = $modelTraspaso->obtenerTotalRecibidosEstacionEntreFechas($zona["idruta"], $fechaInicial, $fechaFinal);
    $totalTraspasosEstaciones = $traspasosEstaciones[0]["total"] ?? 0;

    $autoconsumosKg = $modelAutoconsumo->obtenerTotalAutoconsumosEstacionesProductoFecha("Gas LP", $fechaInicial, $fechaFinal, $zona["idruta"]);
    $totalAutoconsumoKg = $autoconsumosKg[0]["total"] * 0.524;

    $totalContableKg = $totalInventarioAnteriorKg + $totalTraspasosEstaciones - $totalVentaKg - $totalAutoconsumoKg;


    $totalPorcentaje = $totalKgInventarioActual / 0.524;
    $totalLitrosReal = array_sum($litrosPorEstacion);
$totalKgReal = $totalLitrosReal * 0.524;
$diferencia = $totalLitrosReal - $totalContableKg;
$diferenciaTotal += $diferencia;

$porcentajeDiferencia = ($totalContableKg != 0) 
    ? (($totalKgReal - $totalContableKg) / $totalContableKg) * 100
    : 0;

?>

<tr class="text-right">
  <td><?php echo number_format($totalVentaKg, 2); ?></td>
  <td><?php echo $zona["clave_ruta"]; ?></td>
  <td><?php echo number_format($totalInventarioAnteriorKg, 2); ?></td>
  <td><?php echo number_format($totalTraspasosEstaciones, 2); ?></td>
  <td><?php echo number_format($totalAutoconsumoKg, 2); ?></td>
  <td><?php echo number_format($totalVentaKg, 2); ?></td>
  <td><?php echo number_format($totalContableKg, 2); ?></td>
<?php foreach ($litrosPorEstacion as $litros): ?>
    <td><?php echo number_format($litros, 2); ?> Lts</td>
<?php endforeach; ?>  <td><?php echo number_format($diferencia, 2); ?></td>
  <td><?php echo number_format($porcentajeDiferencia, 2); ?>%</td>
</tr>
<?php endforeach; ?>


  <tr class="text-right">
    <td colspan="8"></td>
    <td>
      <?php echo number_format($diferenciaTotal, 2) ?>
    </td>
  </tr>
</tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script type="text/JavaScript">
  $(document).ready(function(){
    if("<?php echo $fechaInicial; ?>" == "<?php echo $fechaFinal; ?>"){
      $('#listaTabla').treetable('expandAll');
    }
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
          , filename: 'inventarioTeoricoZona'
    });
  });

  function abrirModalEditarInventario(rutaId,productoId,inventario){
    $("#modalEditarInventario").modal("show");
    $("#rutaId").val(rutaId);
    $("#productoId").val(productoId);
    $("#inventarioNuevo").val(inventario);
    
  }
</script>