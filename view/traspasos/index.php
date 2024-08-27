<?php
date_default_timezone_set('America/Mexico_City');
$modelZona = new ModelZona();
$modelTraspaso = new ModelTraspaso();
//Búsqueda de datos
$diaInicio = (!empty($_GET["diaInicio"])) ? $_GET["diaInicio"] : 1;
$mesInicio = (!empty($_GET["mesInicio"])) ? $_GET["mesInicio"] : date("m");
$anioInicio = (!empty($_GET["anioInicio"])) ? $_GET["anioInicio"] : date("Y");

$fechaInicio = $anioInicio . "-" . $mesInicio . "-" . $diaInicio;

$diaFin = (!empty($_GET["diaFin"])) ? $_GET["diaFin"] : date("d");
$mesFin = (!empty($_GET["mesFin"])) ? $_GET["mesFin"] : date("m");
$anioFin = (!empty($_GET["anioFin"])) ? $_GET["anioFin"] : date("Y");

$fechaFin = $anioFin . "-" . $mesFin . "-" . $diaFin;

if($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv"){
  $zonaId =  isset($_GET["zona"]) ? $_GET["zona"]:"";
}else{
  $zonaId = $_SESSION["zonaId"];
}

$tipoBusqueda = (!empty($_GET["tipoBusqueda"])) ? $_GET["tipoBusqueda"] : "recibidos";

$zonas = $modelZona->obtenerZonasGas();

if ($tipoBusqueda == "recibidos") $traspasos = $modelTraspaso->obtenerRecibidosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin);
else $traspasos = $modelTraspaso->obtenerEnviadosZonaIdEntreFechas($zonaId, $fechaInicio, $fechaFin);

$totalCantidad = 0;

$anio = date("Y");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Traspasos</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Traspasos</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=traspasos/nuevo.php">Nuevo</a>
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
          <div class="row">
            <div class="col-md-1">
              <label>Buscar por:</label>
            </div>
            <div class="col-md-2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="recibidos" <?php echo ($tipoBusqueda == "recibidos") ? "checked" : "" ?>>
                <label class="form-check-label">Recibidos</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipoBusqueda" id="tipoBusqueda" value="enviados" <?php echo ($tipoBusqueda == "enviados") ? "checked" : "" ?>>
                <label class="form-check-label">Enviados</label>
              </div>
            </div>
          </div>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona" id="zona">
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
                <label>Fecha Inicio:</label>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="diaInicio" id="diaInicio">
                  <?php
                  for ($i = 1; $i <= 31; $i++) {
                    echo "<option value=" . $i;
                    if ($diaInicio == $i) {
                      echo " selected='selected'";
                    }
                    echo ">" . $i . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesInicio" id="mesInicio">
                  <?php
                  $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                  for ($j = 1; $j <= 12; $j++) {
                    echo "<option value=" . $j;
                    if ($mesInicio == $j) {
                      echo " selected='selected'";
                    }
                    echo ">" . $meses[$j] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioInicio" id="anioInicio">
                  <?php
                  for ($k = $anio; $k >= 2010; $k--) {
                    echo "<option value=" . $k;
                    if ($anioInicio == $k) {
                      echo " selected='selected'";
                    }
                    echo ">" . $k . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Fecha Fin:</label>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="diaFin" id="diaFin">
                  <?php
                  for ($i = 1; $i <= 31; $i++) {
                    echo "<option value=" . $i;
                    if ($diaFin == $i) {
                      echo " selected='selected'";
                    }
                    echo ">" . $i . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mesFin" id="mesFin">
                  <?php
                  $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                  for ($j = 1; $j <= 12; $j++) {
                    echo "<option value=" . $j;
                    if ($mesFin == $j) {
                      echo " selected='selected'";
                    }
                    echo ">" . $meses[$j] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-1">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anioFin" id="anioFin">
                  <?php
                  for ($k = $anio; $k >= 2010; $k--) {
                    echo "<option value=" . $k;
                    if ($anioFin == $k) {
                      echo " selected='selected'";
                    }
                    echo ">" . $k . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="traspasos/index.php" />
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
        <h6 class="m-0 font-weight-bold text-primary">Lista de Traspasos <?php echo $tipoBusqueda ?></h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
          <div class="row">
            <div class="alert alert-warning col-md-12" role="alert">
              Se pueden eliminar los traspasos enviados aunque ya se aceptaran mientras la fecha de creación no sea mayor a 3 días.
            </div>
          </div>
        <?php endif; ?>
        <div class="row">
          <table class="table table-bordered table-sm table-responsive" id="listaTabla" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha</th>
                <th>Zona Origen</th>
                <th>Zona Destino</th>
                <th>Cantidad</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($traspasos as $traspaso) :
                $totalCantidad = $totalCantidad + $traspaso["cantidad"];
              ?>
                <tr align='center'>
                  <td><?php echo $traspaso["fecha"] ?></td>
                  <td><?php echo $traspaso["zona_origen"] ?></td>
                  <td><?php echo $traspaso["zona_destino"] ?></td>
                  <td class="text-right"><?php echo $traspaso["cantidad"] ?></td>
                  <td><?php echo $traspaso["estatus_nombre"] ?></td>
                  <td>
                    <?php
                    if ($_SESSION["tipoUsuario"] == "u" && $tipoBusqueda == "recibidos" && $traspaso["estatus_id"] == 2) : ?>
                      <button class='btn btn-sm btn-warning' type='button' data-toggle="tooltip" title="Aceptar" onclick="aceptar('<?php echo $traspaso['idtraspaso']; ?>');"><i class='fas fa-check fa-sm'></i></button>
                      <button class='btn btn-sm btn-primary' type='button' data-toggle="tooltip" title="Rechazar" onclick="rechazar('<?php echo $traspaso['idtraspaso']; ?>');"><i class='fas fa-times fa-sm'></i></button>
                    <?php elseif ((($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "inv") && $tipoBusqueda == "enviados" && date_diff(date_create($traspaso["fecha"]), date_create(date("Y-m-d")))->format('%d') <= 3) || ($_SESSION["tipoUsuario"] == "u" && $tipoBusqueda == "enviados" && $traspaso["estatus_id"] == 2)) : ?>
                      <button class='btn btn-sm btn-primary' type='button' data-toggle="tooltip" title="Eliminar" onclick="eliminar('<?php echo $traspaso['idtraspaso']; ?>');"><i class='fas fa-trash fa-sm'></i></button>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
              <tr class="bg-light">
                <td><b>Total</b></td>
                <td></td>
                <td></td>
                <td class="text-right"><b><?php echo number_format(($totalCantidad), 2) ?></b></td>
                <td></td>
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

  $('#listaTabla').DataTable({
    "pageLength": 50,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  function aceptar(id) {
    alertify.confirm("¿Realmente desea aceptar el traspaso?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Traspasos/Aceptar.php",
            data: {
              id: id
            },
            success: function(data) {
              alertify.success("Traspaso aceptado");
              location.reload();
            }
          });
        },
        function() {})
      .set({
        title: "Aceptar traspaso"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

  function rechazar(id) {
    alertify.confirm("¿Realmente desea rechazar el traspaso?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Traspasos/Rechazar.php",
            data: {
              id: id
            },
            success: function(data) {
              alertify.success("Traspaso rechazado");
              location.reload();
            }
          });
        },
        function() {})
      .set({
        title: "Rechazar traspaso"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar el traspaso?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Traspasos/Eliminar.php",
            data: {
              id: id
            },
            success: function(data) {
              alertify.success("Traspaso eliminado");
              location.reload();
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar traspaso"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>