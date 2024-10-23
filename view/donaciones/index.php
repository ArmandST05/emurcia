<?php
$modelZona = new ModelZona();
$modelDonacion = new ModelDonacion();
$fechaInicial = (isset($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-d");
$fechaFinal = (isset($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : date("Y-m-d");

$zonaId = "";
$zonaNombre = "";
if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonas = $modelZona->obtenerZonasTodas();
  if (!empty($_GET["zona"])) {
    $zonaId = $_GET["zona"];
    $zonaNombre = $modelZona->obtenerZonaId($zonaId);
    $zonaNombre = $zonaNombre["nombre"];
  }
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];
}

$donaciones = $modelDonacion->obtenerDonacionesZonaEntreFechas($fechaInicial, $fechaFinal, $zonaId);
$totalKg = 0;

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Donaciones</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Donaciones</h1>
  <?php if ($_SESSION["tipoUsuario"] == "u") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=donaciones/nuevo.php">Nueva</a>
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
      <div class="card-body">
        <form action='index.php' method='GET'>
          <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-3">
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
          <div class="row">
            <div class="col-md-2">
              <input type='hidden' name='action' id='action' value="donaciones/index.php" />
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
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista Donaciones</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <form action="../controller/Donaciones/ReporteDonaciones.php" method="POST">
              <input type="hidden" name="zonaNombre" value="<?php echo $zonaNombre; ?>">
              <input type="hidden" name="zonaId" value="<?php echo $zonaId; ?>">
              <input type="hidden" name="fechaInicial" value="<?php echo $fechaInicial; ?>">
              <input type="hidden" name="fechaFinal" value="<?php echo $fechaFinal; ?>">
              <button class="btn btn-sm btn-primary" type="submit"><i class="far fa-file-pdf"></i>Guardar PDF</button>
            </form>
            <button class="btn btn-warning btn-sm" id="btnExport"><i class="far fa-file-excel"></i> Exportar a Excel</button>
          </div>
        </div>
        <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Kilogramos</th>
              <th>Zona</th>
              <th>Comentario</th>
              <th>Comprobante</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($donaciones as $donacion) :
              $totalKg += $donacion["kilogramos"];
            ?>
              <tr>
                <td><?php echo $donacion["fecha"] ?></td>
                <td class="text-right"><?php echo $donacion["kilogramos"] ?></td>
                <td><?php echo $zonaNombre; ?></td>
                <td><?php echo $donacion["comentarios"] ?></td>
                <td>
                    <a href="<?php echo 'https://cgtest.v2technoconsulting.com/view/donaciones/comprobantes/' . basename($donacion['comprobante_donaciones']); ?>" download>
                        Descargar comprobante
                     </a>
                </td>


                <td>
                  <?php if ($_SESSION['tipoUsuario'] == "u" || $_SESSION["tipoUsuario"] == "su") : ?>
                    <button class='btn btn-sm btn-primary' type='button' onclick='eliminarDonacion("<?php echo $donacion['iddonacion'] ?>");'><i class='fas fa-trash'></i></button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="bg-light">
              <td><b>Total</b></td>
              <td class="text-right"><b><?php echo number_format($totalKg, 2) ?></b></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
  });

  $('#listaTabla').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  }); 

  $("#btnExport").click(function (e) {
      $("#listaTabla").btechco_excelexport({
        containerid: "listaTabla"
        , datatype: $datatype.Table
        , filename: 'ReporteDonaciones-'+"<?php echo $zonaNombre ?>"+" De "+"<?php echo $fechaInicial ?>"+" A "+"<?php echo $fechaFinal ?>"
      });
  });

  function eliminarDonacion(donacionId) {
    alertify.confirm("¿Realmente desea eliminar la donación seleccionada?",
      function() {
        $.ajax({
            type: "POST",
            url: "../controller/Donaciones/EliminarDonacion.php",
            data: {
              id: donacionId
            },
            success: function(data) {
              alertify.success("Donación eliminada exitosamente");
              location.reload();
            }
          });
      },
      function() {
      })
      .set({
        title: "Eliminar donación"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>