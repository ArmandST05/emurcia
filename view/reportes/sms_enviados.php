<?php
$modelSmsEnviado = new ModelSmsEnviado();
//Búsqueda de datos
$fechaMinima = date(("Y-m-d"), strtotime("-7 days"));
$fechaInicial = (isset($_GET["fechaInicial"])) ? $_GET["fechaInicial"] : date("Y-m-01");
$ultimoDiaMes = new DateTime();
$fechaFinal = (isset($_GET["fechaFinal"])) ? $_GET["fechaFinal"] : $ultimoDiaMes->format('Y-m-t');

$productoId = (isset($_GET["producto"])) ? $_GET["producto"] : 0;
$rutaId = (isset($_GET["ruta"])) ? $_GET["ruta"] : 0;

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc") {
  $smsEnviados = $modelSmsEnviado->obtenerEnviadosResumenFechas($fechaInicial, $fechaFinal);
} else {
  $zonaId = $_SESSION['zonaId'];
  $zonaNombre = $_SESSION['zona'];

  $smsEnviados = $modelSmsEnviado->obtenerEnviadosResumenZonaFechas($zonaId,$fechaInicial, $fechaFinal);
}

$totalGralSmsPedidos = 0;
$totalGralSmsProximosPedidos = 0;
$totalGralSms = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Reporte SMS</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Reporte SMS</h1>
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
              <input type='hidden' name='action' id='action' value="reportes/sms_enviados.php" />
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
        <h6 class="m-0 font-weight-bold text-primary">Lista SMS Enviados</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 offset-md-10">
            <button class="btn btn-sm btn-warning" id="btnExport"><i class="far fa-file-excel"></i> Exportar Excel</button>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <table id="listaTabla" class="table table-bordered table-sm table-responsive" style="width:100%">
              <thead>
                <tr>
                  <th>Zona</th>
                  <th>SMS Aviso a Unidades</th>
                  <th>SMS Próximos Pedidos</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($smsEnviados as $zona) :
                  $totalSms = $zona["total_pedidos"]+$zona["total_proximos_pedidos"];
                  $totalGralSmsPedidos += $zona["total_pedidos"];
                  $totalGralSmsProximosPedidos += $zona["total_proximos_pedidos"];
                  $totalGralSms += $totalSms;
                ?>
                  <tr>
                    <td><?php echo $zona["zona_nombre"]; ?></td>
                    <td><?php echo number_format($zona["total_pedidos"]); ?></td>
                    <td><?php echo number_format($zona["total_proximos_pedidos"]); ?></td>
                    <td><?php echo $totalSms; ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <th>Zona</th>
                  <th>SMS Aviso a Unidades</th>
                  <th>SMS Próximos Pedidos</th>
                  <th>Total</th>
                </tr>
                <tr class="bg-light text-right">
                  <td><b>TOTAL</b></td>
                  <td><b><?php echo $totalGralSmsPedidos ?></b></td>
                  <td><b><?php echo $totalGralSmsProximosPedidos ?></b></td>
                  <td><b><?php echo $totalGralSms ?></b></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
  });
  
  //Exportar a Excel
  $("#btnExport").click(function (e) {
    let fechaInicial = "<?php echo $fechaInicial ?>";
    let fechaFinal = "<?php echo $fechaFinal ?>";
    setTimeout(function(){
        $("#listaTabla").btechco_excelexport({
          containerid: "listaTabla"
          , datatype: $datatype.Table
          , filename: 'Reporte SMS De '+fechaInicial+' A '+fechaFinal
        });
      }, 12000);
  });

</script>