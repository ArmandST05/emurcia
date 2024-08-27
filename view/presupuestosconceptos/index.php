<?php
$modelZona = new ModelZona();
$modelRuta = new ModelRuta();
$modelPresupuestoConcepto = new ModelPresupuestoConcepto();
$modelGasto = new ModelGasto();
$modelConceptoGasto = new ModelConceptoGasto();
$anio = date("Y");
$meses = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

$mesInicial = (!empty($_GET["mesInicial"])) ? $_GET["mesInicial"] : "01";
$anioInicial = (!empty($_GET["anioInicial"])) ? $_GET["anioInicial"] : date("Y");
$fechaInicial = $anioInicial . "-" . $mesInicial . "-01";

$mesFinal = (!empty($_GET["mesFinal"])) ? $_GET["mesFinal"] : "06";
$anioFinal = (!empty($_GET["anioFinal"])) ? $_GET["anioFinal"] : date("Y");
$fechaFinal = $anioFinal . "-" . $mesFinal . "-31";

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "ga" || $_SESSION["tipoUsuario"] == "inv") {
  $zonas = $modelZona->listaTodas();
  $zonaId = (!empty($_GET["zona"])) ? $_GET["zona"] : "";
} else $zonaId = $_SESSION["zonaId"];

$tiposGastoData = $modelGasto->listaTiposGasto();
$tipoGastoId = (isset($_GET["tipoGasto"])) ? $_GET["tipoGasto"] : 1;
$tipoGastoNombre = $tiposGastoData[array_search($tipoGastoId, array_column($tiposGastoData, "idtipogasto"))]["nombre"];


//Obtener rutas si es presupuesto por punto venta
if ($tiposGastoId = 2) {
  $rutas = $modelRuta->listaPorZonaEstatus($zonaId, 1);
  if (!empty($_GET["ruta"])) {
    $rutaId = $_GET["ruta"];
    $rutaDatos = $modelRuta->obtenerRutaId($rutaId);
    if ($rutaDatos) $rutasPresupuesto[] = $rutaDatos;
    else $rutasPresupuesto = $rutas;
  } else {
    $rutaId = 0;
    $rutasPresupuesto = $rutas;
  }
}

$conceptos = $modelConceptoGasto->listaPorTipoGastoEstatus($tipoGastoId, 1);

setlocale(LC_ALL, "es_ES");
$periodoFechas = new DatePeriod(
  new DateTime($fechaInicial),
  DateInterval::createFromDateString('1 month'),
  new DateTime($fechaFinal)
);

$mesesSeleccionados = [];
foreach ($periodoFechas as $periodo) {
  $mesesSeleccionados[$periodo->format("Y-n")] = ["nombre" => ucfirst(strftime("%B", $periodo->getTimestamp())) . " " . $periodo->format("Y"), "mes" => $periodo->format("n"), "anio" => $periodo->format("Y")];
}
?>

<style scoped>
  .fixed-column {
    position: sticky !important;
    left: 0px !important;
  }

  .first-column {
    background-color: white !important;
  }
</style>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Gastos</a> /
    <a href="#">Presupuesto Conceptos</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Presupuesto Conceptos</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header - Dropdown -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 text-primary">Buscar</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body" name="otra" id="otra">
        <form action='index.php' method='GET'>
          <input type='hidden' name='action' id='action' value="presupuestosconceptos/index.php" />
          <div class="row">
            <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "ga" || $_SESSION["tipoUsuario"] == "inv") : ?>
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" required>
                    <option selected disabled>Selecciona una zona</option>
                    <?php foreach ($zonas as $zona) : ?>
                      <option value="<?php echo $zona['idzona'] ?>" <?php echo ($zonaId == $zona['idzona']) ? "selected" : "" ?>>
                        <?php echo strtoupper($zona["nombre"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>
            <div class="col-md-1">
              <div class="form-group">
                <label>Gasto:</label>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <select class="form-control form-control-sm" name="tipoGasto">
                  <?php foreach ($tiposGastoData as $tipo) : ?>
                    <option value="<?php echo $tipo['idtipogasto'] ?>" <?php echo ($tipoGastoId == $tipo['idtipogasto']) ? "selected" : "" ?>>
                      <?php echo strtoupper($tipo["nombre"]) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <?php if ($tipoGastoId == 2) : ?>
              <div class="col-md-1">
                <div class="form-group">
                  <label>Ruta:</label>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="ruta">
                    <option value="0">Todas</option>
                    <?php foreach ($rutas as $ruta) : ?>
                      <option value="<?php echo $ruta['idtipogasto'] ?>" <?php echo ($rutaId == $ruta['idtipogasto']) ? "selected" : "" ?>>
                        <?php echo strtoupper($ruta["clave_ruta"]) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>
            <?php endif; ?>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Desde:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesInicial" id="mesInicial">
                  <?php foreach ($meses as $claveMes => $mes) : ?>
                    <option value="<?php echo $claveMes; ?>" <?php echo ($mesInicial == $claveMes) ? "selected" : ""; ?>>
                      <?php echo $mes; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioInicial" id="anioInicial">
                  <?php for ($k = $anio; $k >= 2010; $k--) : ?>
                    <option value="<?php echo $k; ?>" <?php echo ($anioInicial == $k) ? "selected" : "" ?>>
                      <?php echo $k ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <label>Hasta:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesFinal" id="mesFinal">
                  <?php foreach ($meses as $claveMes => $mes) : ?>
                    <option value="<?php echo $claveMes; ?>" <?php echo ($mesFinal == $claveMes) ? "selected" : ""; ?>><?php echo $mes ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioFinal" id="anioFinal">
                  <?php for ($k = $anio; $k >= 2010; $k--) : ?>
                    <option value="<?php echo $k; ?>" <?php echo ($anioFinal == $k) ? "selected" : ""; ?>><?php echo $k; ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-11">
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
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between form-inline">
        <h6 class="m-0 text-primary">PRESUPUESTO <?php echo strtoupper($tipoGastoNombre) ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php if ($zonaId) : ?>
          <div class="row">
            <div class="col-md-2 offset-md-10">
              <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
            </div>
          </div>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Para agregar un nuevo presupuesto, haz clic en el icono <i class="fas fa-pencil-alt fa-sm"></i> en el mes deseado. Después haz clic sobre los conceptos que se incluirán y clic en Guardar.<br>
              Si quieres borrar conceptos de un presupuesto, haz clic en <i class="fas fa-pencil-alt fa-sm"></i> y deselecciona los conceptos innecesarios. Después en Guardar.            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <table id="listaTabla" class="table table-bordered table-sm table-responsive text-left" style="width:100%">
                <thead>
                  <tr>
                    <?php if ($tipoGastoId == 2) : ?>
                      <th class="text-center">Ruta</th>
                    <?php endif; ?>
                    <?php foreach ($mesesSeleccionados as $claveMes => $mes) : ?>
                      <th class="text-center"><?php echo $mes["nombre"] ?></th>
                    <?php endforeach; ?>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($tipoGastoId == 1) : //Presupuesto administrativo
                  ?>
                    <tr>
                      <?php foreach ($mesesSeleccionados as $claveMes => $mes) :
                        $presupuestoMes = $modelPresupuestoConcepto->obtenerZonaMesAnio($tipoGastoId, $zonaId, $mes['mes'], $mes['anio']);
                      ?>
                        <td class="presupuesto">
                          <?php if($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u" || $_SESSION["tipoUsuario"] == "ga"): ?>
                          <button class="btn btn-sm text-right" onclick="abrirModalPresupuestoMes('<?php echo $mes['mes'] ?>','<?php echo $mes['anio'] ?>','<?php echo $mes['nombre'] ?>')" tooltip="Editar Presupuesto"><i class='fas fa-pencil-alt fa-sm'></i></button>
                          <?php endif;?>
                          <div id="pres<?php echo $claveMes ?>">
                            <?php
                            foreach ($presupuestoMes as $presupuesto) : ?>
                              <label class="border-bottom prescon<?php echo $claveMes ?>" data-concepto-id="<?php echo $presupuesto['concepto_id'] ?>"><?php echo $presupuesto['concepto_nombre'] ?></label><br>
                            <?php endforeach; ?>
                          </div>
                        </td>
                      <?php endforeach; ?>
                    </tr>
                  <?php elseif ($tipoGastoId == 2) : //Presupuesto rutas
                  ?>
                    <?php foreach ($rutasPresupuesto as $claveRuta => $rutaPresupuesto) : ?>
                      <tr>
                        <td><b><?php echo $rutaPresupuesto['clave_ruta'] ?></b></td>
                        <?php foreach ($mesesSeleccionados as $claveMes => $mes) :
                          $presupuestoMes = $modelPresupuestoConcepto->obtenerZonaRutaMesAnio($zonaId, $rutaPresupuesto['idruta'], $mes['mes'], $mes['anio']);
                        ?>
                          <td class="presupuesto">
                            <button class="btn btn-sm text-right" onclick="abrirModalPresupuestoMes('<?php echo $mes['mes'] ?>','<?php echo $mes['anio'] ?>','<?php echo $mes['nombre'] ?>','<?php echo $rutaPresupuesto['idruta'] ?>','<?php echo $rutaPresupuesto['clave_ruta'] ?>')" tooltip="Editar Presupuesto"><i class='fas fa-pencil-alt fa-sm'></i></button>
                            <div id="pres<?php echo $rutaPresupuesto['idruta'] ?><?php echo $claveMes ?>">
                              <?php
                              foreach ($presupuestoMes as $presupuesto) : ?>
                                <label class="border-bottom prescon<?php echo $rutaPresupuesto['idruta'] ?><?php echo $claveMes ?>" data-concepto-id="<?php echo $presupuesto['concepto_id'] ?>"><?php echo $presupuesto['concepto_nombre'] ?></label><br>
                              <?php endforeach; ?>
                            </div>
                          </td>
                        <?php endforeach; ?>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- Modal Seleccionar Conceptos -->
<div class="modal fade" id="modalPresupuestoMes" tabindex="-1" role="dialog" aria-labelledby="modalConceptos" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modalConceptos">
          <h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </h6>
          </h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <table id="listaTablaConceptos" class="table table-bordered table-sm table-responsive">
              <thead>
                <tr>
                  <th></th>
                  <th>Nombre</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($conceptos as $concepto) : ?>
                  <tr id="con<?php echo $concepto['idconceptogasto'] ?>">
                    <td style="visibility:collapse;"><?php echo $concepto["idconceptogasto"] ?></td>
                    <td><?php echo $concepto["nombre"] ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" value="" id="mesEditado">
        <input type="hidden" value="" id="anioEditado">
        <input type="hidden" value="" id="rutaIdEditado">
        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="guardarPresupuesto()">Guardar</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal Seleccionar conceptos -->
<style>
  .selected {
    font-weight: bold;
  }

  <?php if ($tipoGastoId == 2) : //Mantener columna de nombre de ruta fijo
  ?>.fixed-column {
    position: sticky !important;
    left: 0px !important;
  }

  .first-column {
    background-color: white !important;
  }

  <?php endif; ?>
</style>

<script type="text/JavaScript">
  $(document).ready(function(){
    var listaTablaConceptos = $('#listaTablaConceptos').DataTable({
      ordering: false,
      dom: 'Blfrtip',
      buttons: [
        { extend: 'selectAll', className: 'btn btn-sm bg-light text-dark' },
        { extend: 'selectNone', className: 'btn btn-sm bg-light text-dark' }
      ],
      select: {
        style: 'multi'
      },
      pageLength: 10,
      language: {
        url: "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json",
        select: {
          rows: {
            _: "%d seleccionados",
          }
        },
        buttons: {
              selectAll: "Seleccionar todo",
              selectNone: "Deseleccionar todo"
          }
      },
    });
  });

  $("#btnExport").click(function (e) {
    $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'PresupuestoConceptos'
    });
  });

    var listaTabla = $('#listaTabla').DataTable({
      paging:   false,
      ordering: false,
      info:     false,
      searching: false,
      editable: true
    });

  function abrirModalPresupuestoMes(mes,anio,nombreMes,rutaId="",rutaNombre="") {
    $('#listaTablaConceptos').DataTable().rows('.selected').deselect().draw();
    //Limpiar filas seleccionadas
    let claveMes = anio+"-"+mes;
    $("#mesEditado").val(mes);
    $("#anioEditado").val(anio);
    $("#rutaIdEditado").val(rutaId);
    $("#modalConceptos").text("Presupuesto "+nombreMes+" "+rutaNombre);//Título del modal

    //Seleccionar filas de acuerdo a lo que tiene el presupuesto
    $(".prescon"+rutaId+claveMes).each(function(){
      $("#con"+$(this).attr('data-concepto-id')).toggleClass('selected');
    });
    $("#modalPresupuestoMes").modal({
      show: true,
      keyboard: false,
      backdrop: 'static'
    });
  }

  function guardarPresupuesto(){
    let selectedRows = $('#listaTablaConceptos').DataTable().rows('.selected');
    let zonaId = "<?php echo $zonaId ?>";
    let tipoGastoId = "<?php echo $tipoGastoId ?>";
    let mes = ""+$("#mesEditado").val();
    let anio = ""+$("#anioEditado").val();
    let rutaId = ""+$("#rutaIdEditado").val();
    let claveMes = anio+"-"+mes;
    let conceptosSeleccionados = 0;
    let conceptosGuardados = 0;
    $.ajax({
      type: "POST",
      url: "../../controller/PresupuestosConcepto/EliminarMes.php",
      data: {
        tipoGastoId: tipoGastoId,
        zonaId: zonaId,
        rutaId: rutaId,
        mes: mes,
        anio: anio
      },
      success: function(data) {
        //ALMACENAR CONCEPTOS SELECCIONADOS

        //Limpiar donde se muestran los conceptos del presupuesto mensual
        $("#pres"+rutaId+claveMes).html(""); 
        selectedRows.every(function(){
          conceptosSeleccionados++;
          var conceptoId = $('#listaTablaConceptos').DataTable().cell(this, 0).data();
          var conceptoNombre = $('#listaTablaConceptos').DataTable().cell(this, 1).data();
          $.ajax({
            type: "POST",
            url: "../../controller/PresupuestosConcepto/InsertarConcepto.php",
            data: {
              zonaId: zonaId,
              rutaId: rutaId,
              mes: mes,
              anio: anio,
              conceptoId: conceptoId
            },
            success: function(data) {
              //Agregar a tabla de presupuestos los conceptos guardados
              let detallePresupuesto = "<label class='border-bottom prescon"+rutaId+claveMes+"' data-concepto-id='"+conceptoId+"'>"+conceptoNombre+"</label><br>"; 
              $("#pres"+rutaId+claveMes).append(detallePresupuesto); 
              conceptosGuardados++; 
            }
          });  
        });
        $("#modalPresupuestoMes").modal('hide');
      },
      fail: function(data){
        alertify.error('El presupuesto no se ha actualizado');
      }
    });
  }
</script>