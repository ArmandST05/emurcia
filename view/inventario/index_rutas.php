<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelInventario = new ModelInventario();
$fechaActual = date("Y-m-d");
$fechaInicial = (!empty($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = (!empty($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-d");

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
  $zonas = $modelZona->obtenerZonasTodas();
} else {
  $zonaId = $_SESSION['zonaId'];
}

$datosInventario = $modelInventario->obtenerReporteInventarioFechas($fechaInicial, $fechaFinal, $zonaId);
$inventarios = array();

foreach ($datosInventario as $dato) {
  $inventarios[$dato["fecha_inventario"]][] = $dato;
}
$rutas = $modelRuta->inventarioRutas($zonaId);

$totalKilos = 0;
$totalLitros = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Inventario Gas</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Inventario Gas</h1>
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
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <input class="form-control form-control-sm" type="date" name="fechaInicial" value="<?php echo $fechaInicial ?>">
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
                <input class="form-control form-control-sm" type="date" name="fechaFinal" value="<?php echo $fechaFinal ?>">
              </div>
            </div>

          </div>
          <input type='hidden' name='action' id='action' value="inventario/index_rutas.php" />
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
<div class="modal fade" id="modalEditarInventario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <input type="number" class="form-control form-control-sm" name="inventarioNuevo" id="inventarioNuevo" min="0" step=".01" value="0">
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
              <th>Fecha</th>
              <th>Ruta/Unidad</th>
              <th>Producto</th>
              <th>Mínimo</th>
              <th>Capacidad</th>
              <th>Kilos</th>
              <th>Litros</th>
              <th>Inventario</th>
              <?php if ($_SESSION["tipoUsuario"] == "u") : ?><th>Acciones</th><?php endif; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($inventarios as $claveInventario => $inventarioFecha) :
              $totalLitrosFecha = 0;
              $totalKilosFecha = 0;
            ?>
              <tr data-tt-id="<?php echo $claveInventario ?>" class="bg-light">
                <td colspan="10"><b><?php echo $claveInventario ?></b></td>
              </tr>
              <?php foreach ($inventarioFecha as $claveRuta => $ruta) :

                $productoId = $ruta["producto_id"];
                $inventarioActual = number_format($ruta["inventario_actual"], 2);

                $totalEntradas = $ruta["total_entradas"];
                $totalSalidas = $ruta["total_salidas"];

                if (number_format($totalSalidas, 2) == 0) $inventarioActual = number_format($totalEntradas, 2);
                else $inventarioActual = number_format(($totalEntradas - $totalSalidas), 2);

                //Si el producto es Lts realizar cálculo de inventario en base a inventario actual y capacidad de Pipa
                if ($productoId == 4) {
                  $litros = (($inventarioActual * $ruta["ruta_capacidad"]) / 100);
                  $kilos = ($litros * .524);
                } 
                elseif($productoId == 6 || $productoId == 7 || $productoId == 8){
                  //Tanques Gasolina
                  $litros = $inventarioActual;
                  $kilos = ($litros * .524);
                }
                else {
                  //Se calcula los litros vendidos por el cilindro
                  $kilos = ($inventarioActual * $ruta["producto_capacidad"]);
                  $litros = ($kilos / .524);
                }
                $totalLitrosFecha += $litros;
                $totalKilosFecha += $kilos;
                $rutaId = $ruta["ruta_id"];
              ?>
                <tr data-tt-id="<?php echo $claveRuta ?>" data-tt-parent-id="<?php echo $claveInventario ?>" class="text-right">
                  <td><?php echo $ruta["fecha_inventario"]; ?></td>
                  <td><?php echo $ruta["ruta_nombre"]; ?></td>
                  <td><?php echo $ruta["producto_nombre"]; ?></td>
                  <td>1</td>
                  <td><?php echo $ruta["ruta_capacidad"]; ?></td>
                  <td><?php echo number_format($kilos, 2); ?></td>
                  <td><?php echo number_format($litros, 2); ?></td>
                  <td><?php echo number_format(floatval($inventarioActual), 2);
                      echo ($productoId == 4) ? "%" : "" ?></td>
                  <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
                    <td class="text-center">
                      <?php if ($fechaActual == $claveInventario) : ?>
                        <button type="button" class="btn btn-sm btn-light" onclick="abrirModalEditarInventario('<?php echo $rutaId ?>','<?php echo $productoId ?>','<?php echo $inventarioActual ?>')">
                          <i class='fas fa-pencil-alt'></i>
                        </button>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="5"><b>Total</b></td>
                <td><b><?php echo number_format($totalKilosFecha, 2) ?></b></td>
                <td><b><?php echo number_format($totalLitrosFecha, 2); ?></b></td>
                <td colspan="2"></td>
              </tr>
            <?php endforeach; ?>
            <!-- <tr class="bg-light">
              <td><b>Total</b></td>
              <td></td>
              <td></td>
              <td></td>
              <td><b><?php echo number_format($totalKilos, 2) ?></b></td>
              <td><b><?php echo number_format($totalLitros, 2); ?></b></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>-->
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

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
          , filename: 'inventarioZona'
    });
  });

  function abrirModalEditarInventario(rutaId,productoId,inventario){
    $("#modalEditarInventario").modal("show");
    $("#rutaId").val(rutaId);
    $("#productoId").val(productoId);
    $("#inventarioNuevo").val(inventario);
    
  }
</script>