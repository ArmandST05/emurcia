<?php
date_default_timezone_set('America/Mexico_City');

// Inicializar modelos
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelInventario = new ModelInventario();
$modelCompania = new ModelCompania();
$modelVenta = new ModelVenta();
$modelAutoconsumo = new ModelAutoconsumo();
$modelTraspaso = new ModelTraspaso();

// Variables de fecha
$fechaActual = date("Y-m-d");
$companias = $modelInventario->obtenerCompaniasInventarioTeorico();
$companiaId = (isset($_GET["compania"])) ? $_GET["compania"] : null;

$mesBusqueda = (isset($_GET["mesInicial"])) ? $_GET["mesInicial"] : date("Y-m");
$fechaInicial = $mesBusqueda . "-01";
$fechaFinal = date("Y-m-t", strtotime($fechaInicial));
$fechaAnterior = date("Y-m-t", strtotime($fechaInicial . ' - 1 month'));

// Inicializar variables para cálculos
$diferenciaTotal = 0;
$totalComprasSucursales = 0;
$totalKilos = 0;
$totalLitros = 0;
$estacionesPorZona = []; // Asegura que la variable esté inicializada

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







// Obtener las ventas de la función
$ventas = $modelVenta->obtenerVentasKgZonaFechaEstaciones($fechaInicial, $fechaFinal);
// Variables para los totales
$totalLitros = 0;
$totalKilos = 0;
$totalCil = 0;
$totalCredito = 0;
$totalDescCredito = 0;
$totalContado = 0;
$totalLtsDescContado = 0;
$totalDescContado = 0;
$totalVenta = 0;
$totalPrecioLleno = 0;
$totalLtsCredito = 0;
$totalLtsContado = 0;
$totalKgZona = 0;

// Agrupar las ventas por fecha
$ventasPorFecha = [];
foreach ($ventas as $venta) {
    $fecha = $venta["fecha"];
    if (!isset($ventasPorFecha[$fecha])) {
        $ventasPorFecha[$fecha] = [];
    }
    $ventasPorFecha[$fecha][] = $venta;
}

// Iterar sobre las fechas agrupadas
foreach ($ventasPorFecha as $fecha => $ventasFecha) {
    foreach ($ventasFecha as $venta) {
        // Obtener la capacidad del producto (si no está disponible, se asigna un valor predeterminado)
        $productoCapacidad = isset($venta["producto_capacidad"]) ? $venta["producto_capacidad"] : 1;

        // Venta de litros en estaciones (producto_id == 4)
       
        if ($venta["producto_id"] == 4) {
            $litros = ($venta["total_rubros_venta"] * $productoCapacidad);
            $totalLitros += $litros;
            $kilos = ($litros * 0.524);
            $totalKilos += $kilos;
        } else {
            // Venta de kg en Cilindreras (producto_id diferente a 4)
            $kilos = ($venta["total_rubros_venta"] * $productoCapacidad);
            $totalKilos += $kilos;
            $litros = ($kilos / 0.524);
            $totalLitros += $litros;
        }

        // Si la venta fue de estaciones (tipo_ruta_id 5), contar los cilindros
        $cilindros = ($venta["tipo_ruta_id"] == 5) ? $venta["total_rubros_venta"] : 0;
        $totalCil += $cilindros;

        // Acumulando kilos en la zona
        $totalKgZona += $kilos;

        // Cálculos para ventas a crédito y contado
        $ltsCredito = ($venta["total_venta_credito"] + $venta["descuento_total_venta_credito"]) / $venta["precio"];
        $ltsContado = ($venta["total_venta_contado"] + $venta["descuento_total_venta_contado"]) / $venta["precio"];

        // Acumulando totales por tipo de venta
        $totalCredito += $venta["total_venta_credito"];
        $totalDescCredito += $venta["descuento_total_venta_credito"];
        $totalContado += $venta["total_venta_contado"];
        $totalLtsDescContado += isset($venta["cantidad_venta_contado"]) ? $venta["cantidad_venta_contado"] : 0;
        $totalDescContado += $venta["descuento_total_venta_contado"];
        $totalVenta += ($venta["total_venta"] - $venta["descuento_total_venta_credito"] - $venta["descuento_total_venta_contado"]);
        $totalPrecioLleno += $venta["total_venta"];

        // Acumulando litros de crédito y contado
        $totalLtsCredito += $ltsCredito;
        $totalLtsContado += $ltsContado;
    }
}
?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Inventario Teórico Estaciones</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Inventario Teórico Estaciones</h1>
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
<!-- Modal -->
<!-- Content Row -->
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
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
          </div>
          <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
  <thead>
    <tr>
      <th>VTA TOTAL</th>
      <th>ESTACIÓN</th>
      <th>Inv Inicial</th>
      <th>C. Interno</th>
      <th>VTAS</th>
      <th>Contable</th>
      <th>Real</th>
      <th>Diferencia</th>
      <th>Traspasos</th> <!-- Nueva columna para traspasos -->
    </tr>
  </thead>
  <tbody>
    <?php 
    foreach ($estacionesPorZona as $zona):
      $totalVentaKg = $modelVenta->rutasVentasEntreFechasEstaciones($zona["idruta"], $fechaInicial, $fechaFinal);
      if (isset($totalVentaKg[0])) {
        $totalVentasKg = $totalVentaKg[0]["total"]; // Accede al primer elemento del array
    } else {
        $totalVentasKg = 0; // Si no hay resultados, asigna 0
    }
    
      // Obtener inventarios anteriores y actuales
      $inventarioAnteriorKg = $modelInventario->obtenerTotalInventarioGasKgZonaFechaEstaciones($zona["idruta"], $fechaAnterior);
      $totalInventarioAnteriorKg = $inventarioAnteriorKg["totalKgZona"];
      
      $inventarioKg = $modelInventario->obtenerTotalInventarioGasKgZonaFechaEstaciones($zona["idruta"], $fechaFinal);
      $totalKgInventarioActual = $inventarioKg["totalKgZona"];
      
      // Obtener autoconsumos
      $autoconsumosKg = $modelAutoconsumo->obtenerTotalAutoconsumosEstacionesProductoFecha($zona["idruta"], "Gas LP", $fechaInicial, $fechaFinal);
      $totalAutoconsumoKg = $autoconsumosKg[0]["total"] * 0.524;

      // Obtener los traspasos usando la función obtenerTotalCantidadEntreFechas
      $traspasosKg = $modelTraspaso->obtenerTotalCantidadEntreFechas($zona["idruta"], $fechaInicial, $fechaFinal);
      $totalTraspasosKg = $traspasosKg[0]["total_cantidad"] * 0.524; // Ajusta si es necesario según la consulta

      // Calcular el total contable
      $totalContableKg = $totalInventarioAnteriorKg - $totalKgZona - $totalAutoconsumoKg - $totalTraspasosKg;

      // Cálculo de la diferencia
      $diferencia = $totalKgInventarioActual - $totalContableKg;
      $diferenciaTotal += $diferencia;
      

    ?>
    <tr class="text-right">
      <td><?php echo number_format($totalVentasKg, 2); ?></td>
      <td><?php echo $zona["clave_ruta"]; ?></td>
      <td><?php echo number_format($totalInventarioAnteriorKg, 2); ?></td>
      <td><?php echo number_format($totalAutoconsumoKg, 2) ?></td>
      <td><?php echo number_format($totalVentasKg, 2); ?></td>
      <td><?php echo number_format($totalContableKg, 2) ?></td>
      <td><?php echo number_format($totalKgInventarioActual, 2); ?></td>
      <td><?php echo number_format($diferencia, 2) ?></td>
      <td><?php echo number_format($totalTraspasosKg, 2); ?></td> <!-- Mostrar los traspasos -->
    </tr>
    <?php endforeach; ?>
    <tr class="text-right">
      <td colspan="8"></td>
      <td><?php echo number_format($diferenciaTotal, 2) ?></td>
    </tr>
  </tbody>
</table>

        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<script type="text/JavaScript">
  $(document).ready(function() {
    if ("<?php echo $fechaInicial; ?>" == "<?php echo $fechaFinal; ?>") {
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
      containerid: "listaTabla",
      datatype: $datatype.Table,
      filename: 'inventarioTeoricoZona'
    });
  });

  function abrirModalEditarInventario(rutaId, productoId, inventario){
    $("#modalEditarInventario").modal("show");
    $("#rutaId").val(rutaId);
    $("#productoId").val(productoId);
    $("#inventarioNuevo").val(inventario);
  }
</script>
