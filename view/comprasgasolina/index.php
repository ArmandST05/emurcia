<?php
$modelZona = new ModelZona();
$modelComprasGasolina = new ModelCompraGasolina();
if ($_SESSION['tipoUsuario'] == "u" && $_SESSION["tipoZona"] != 2) {
  echo "<script> 
      alert('Tu zona no vende GASOLINA... Redireccionando compras de gas');
      window.location.href = 'index.php?action=comprasgas/index.php';
    </script>";
}

$zonas = $modelZona->obtenerZonasGasolina();

$day = date("d");
$mes = date("m");
$anio = date("Y");

$month = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zonaId = isset($_GET["zona"]) ? $_GET["zona"] : "";
} else {
  $zona = $_SESSION["zona"];
  $zonaId = $_SESSION["zonaId"];
}

$mes = (!empty($_GET["mes"])) ? $_GET["mes"] : $anio = date("m");
$anio = (!empty($_GET["anio"])) ? $_GET["anio"] : $anio = date("Y");
$fecha = $anio . "-" . $mes . "-01";

$comprasMagna = $modelComprasGasolina->obtenerZonaProducto($zonaId, 6, $fecha);
$comprasPremium = $modelComprasGasolina->obtenerZonaProducto($zonaId, 7, $fecha);
$comprasDiesel = $modelComprasGasolina->obtenerZonaProducto($zonaId, 8, $fecha);
$comprasAceite = $modelComprasGasolina->obtenerZonaProducto($zonaId, 9, $fecha);

$importe_totall = 0;
$litros_total = 0;
$tar_to = 0;

$importe_totallp = 0;
$litros_totalp = 0;
$tar_top = 0;

$importe_totalla = 0;
$litros_totala = 0;
$tar_toa = 0;

$importe_totalld = 0;
$litros_totald = 0;
$tar_tod = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Compras Gasolina</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Compras Gasolina</h1>
  <?php if ($_SESSION["tipoUsuario"] != "inv") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=comprasgasolina/nuevo.php">Nuevo</a>
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
          <?php if ($_SESSION['tipoUsuario'] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") : ?>
            <div class="row">
              <div class="col-md-1">
                <div class="form-group">
                  <label>Zona:</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <select class="form-control form-control-sm" name="zona">
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
          <div class="row">
            <div class="col-md-1">
              <div class="form-group">
                <label>Fecha:</label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="mes" id="mes" onChange="">
                  <?php
                  $meses = ["1" => "Enero", "2" => "Febrero", "3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio", "7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];
                  for ($j = 1; $j <= 12; $j++) {
                    echo "<option value=" . $j;
                    if ($mes == $j) {
                      echo " selected='selected'";
                    }
                    echo ">" . $meses[$j] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <select class="form-control form-control-sm" name="anio" id="anio" onChange="">
                  <?php
                  for ($k = $anio; $k >= 2010; $k--) {
                    echo "<option value=" . $k;
                    if ($anio == $k) {
                      echo " selected='selected'";
                    }
                    echo ">" . $k . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="comprasgasolina/index.php" />
          <div class="row">
            <div clas="col-md-1 offset-md-10">
              <input class="btn btn-primary btn-sm" type='submit' id='busqueda' value='Buscar'>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Content Row -->
<div id="dvData">
  <div class="row">
    <!-- Card -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">MAGNA</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <button class="btn btn-sm btn-warning" id="btnExport">Exportar a Excel</button>
          <table class="table table-bordered table-sm table-responsive w-100" id="datosexcel" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha Compra</th>
                <th>Tipo Producto</th>
                <th>No. Factura</th>
                <th>Chofer</th>
                <th>Fecha Descarga</th>
                <th>Precio</th>
                <th>Litros</th>
                <th>Importe</th>
                <th>Tarifa</th>
                <th>Fecha Pago</th>
                <th>Zona</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($comprasMagna as $datos) : ?>
                <tr align='center'>
                  <td><?php echo $datos["fecha"] ?></td>
                  <td><?php echo strtoupper($datos["producto_nombre"]) ?></td>
                  <td><?php echo $datos["num_factura"] ?></td>
                  <td><?php echo $datos["chofer"] ?></td>
                  <td><?php echo $datos["fecha_descarga"] ?></td>
                  <td><?php echo $datos["precio"] ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["litros"], 0) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["importe"], 5) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["tarifa"], 5) ?></td>
                  <td><?php echo $datos["fecha_pago"] ?></td>
                  <td><?php echo strtoupper($datos["zona_nombre"]) ?></td>
                </tr>
              <?php
                $importe = $datos["importe"];
                $importe_totall = $importe_totall + $importe;
                $litros = $datos["litros"];
                $litros_total = $litros_total + $litros;
                $tar = $datos["tarifa"];
                $tar_to = $tar_to + $tar;
              endforeach;
              ?>
              <tr class="bg-light">
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="text-align:right">
                  <font><strong><?php echo number_format(($litros_total), 0) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($importe_totall), 2) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($tar_to), 2) ?></strong></font>
                </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- Card -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">PREMIUM</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <table class="table table-bordered table-sm table-responsive w-100" id="datosexcel" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha compra</th>
                <th>Tipo Producto</th>
                <th>No. Factura</th>
                <th>Chofer</th>
                <th>Fecha descarga</th>
                <th>Precio</th>
                <th>Litros</th>
                <th>Importe</th>
                <th>Tarifa</th>
                <th>Zona</th>
                <th>Fecha pago</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($comprasPremium as $datos) : ?>
                <tr align='center'>
                  <td><?php echo $datos["fecha"] ?></td>
                  <td><?php echo strtoupper($datos["producto_nombre"]) ?></td>
                  <td><?php echo $datos["num_factura"] ?></td>
                  <td><?php echo $datos["chofer"] ?></td>
                  <td><?php echo $datos["fecha_descarga"] ?></td>
                  <td><?php echo $datos["precio"] ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["litros"], 0) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["importe"], 5) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["tarifa"], 5) ?></td>
                  <td><?php echo strtoupper($datos["zona_nombre"]) ?></td>
                  <td><?php echo $datos["fecha_pago"] ?></td>
                </tr>
              <?php
                $importep = $datos["importe"];
                $importe_totallp = $importe_totallp + $importep;
                $litrosp = $datos["litros"];
                $litros_totalp = $litros_totalp + $litrosp;
                $tarp = $datos["tarifa"];
                $tar_top = $tar_top + $tarp;
              endforeach; ?>
              <tr class="bg-light">
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                </td>
                <td style="text-align:right">
                  <font><strong><?php echo number_format(($litros_totalp), 0) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($importe_totallp), 2) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($tar_top), 2) ?></strong></font>
                </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- Card -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">DIESEL</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <table class="table table-bordered table-sm table-responsive w-100" id="datosexcel" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha compra</th>
                <th>Tipo producto</th>
                <th>No. Factura</th>
                <th>Chofer</th>
                <th>Fecha descarga</th>
                <th>Precio</th>
                <th>Litros</th>
                <th>Importe</th>
                <th>Tarifa</th>
                <th>Zona</th>
                <th>Fecha pago</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($comprasDiesel as $datos) : ?>
                <tr align='center'>
                  <td><?php echo $datos["fecha"] ?></td>
                  <td><?php echo strtoupper($datos["producto_nombre"]) ?></td>
                  <td><?php echo $datos["num_factura"] ?></td>
                  <td><?php echo $datos["chofer"] ?></td>
                  <td><?php echo $datos["fecha_descarga"] ?></td>
                  <td><?php echo $datos["precio"] ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["litros"], 0) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["importe"], 5) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["tarifa"], 5) ?></td>
                  <td><?php echo strtoupper($datos["zona_nombre"]) ?></td>
                  <td><?php echo $datos["fecha_pago"] ?></td>
                </tr>
              <?php
                $imported = $datos["importe"];
                $importe_totalld = $importe_totalld + $imported;
                $litrosd = $datos["litros"];
                $litros_totald = $litros_totald + $litrosd;
                $tard = $datos["tarifa"];
                $tar_tod = $tar_tod + $tard;
              endforeach;
              ?>
              <tr class="bg-light">
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                </td>
                <td style="text-align:right">
                  <font><strong><?php echo number_format(($litros_totald), 0) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($importe_totalld), 2) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($tar_tod), 2) ?></strong></font>
                </td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <!-- Card -->
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">ACEITE</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <table class="table table-bordered table-sm table-responsive w-100" id="datosexcel" name="listaTabla">
            <thead>
              <tr>
                <th>Fecha compra</th>
                <th>Tipo producto</th>
                <th>No. Factura</th>
                <th>Chofer</th>
                <th>Fecha descarga</th>
                <th>Precio</th>
                <th>Litros</th>
                <th>Importe</th>
                <th>Tarifa</th>
                <th>Zona</th>
                <th>Fecha pago</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($comprasAceite as $datos) : ?>
                <tr align='center'>
                  <td><?php echo $datos["fecha"] ?></td>
                  <td><?php echo strtoupper($datos["nombre"]) ?></td>
                  <td><?php echo $datos["num_factura"] ?></td>
                  <td><?php echo $datos["chofer"] ?></td>
                  <td><?php echo $datos["fecha_descarga"] ?></td>
                  <td><?php echo number_format($datos["precio"], 2) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["litros"], 0) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["importe"], 2) ?></td>
                  <td style='text-align:right'><?php echo number_format($datos["tarifa"], 5) ?></td>
                  <td><?php echo strtoupper($datos["zona_nombre"]) ?></td>
                  <td><?php echo $datos["fecha_pago"] ?></td>
                </tr>
              <?php
                $importea = $datos["importe"];
                $importe_totalla = $importe_totalla + $importea;
                $litrosa = $datos["litros"];
                $litros_totala = $litros_totala + $litrosa;
                $tara = $datos["tarifa"];
                $tar_toa = $tar_toa + $tara;
              endforeach; ?>
              <tr class="bg-light">
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                </td>
                <td style="text-align:right">
                  <font><strong></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($importe_totalla), 2) ?></strong></font>
                </td>
                <td style="text-align:right">
                  <font><strong>$<?php echo number_format(($tar_to), 2) ?></strong></font>
                </td>
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
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });

  $("#btnExport").click(function(e) {
    $("#datosexcel").btechco_excelexport({
      containerid: "datosexcel",
      datatype: $datatype.Table,
      filename: 'comprasgasolina'
    });
  });

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    var regex2 = /[ -~]/;
    if (regex2.test(key) && !regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) {
        theEvent.preventDefault();
      }
    }
  }

  function SoloNumerosDecimales3(e, valInicial, nDecimal) {
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    nEntero = 2;

    //el tab
    if (tecla_codigo === 8)
      return true;

    if (valInicial !== obj.value) {
      var TControl = obj.value.length;
      if (existePto === false && tecla_codigo !== 46) {
        if (TControl === nEntero) {
          obj.value = obj.value + ".";
        }
      }

      if (existePto === true) {
        var subVal = obj.value.substring(obj.value.indexOf(".") + 1, obj.value.length);

        if (subVal.length > 1) {
          return false;
        }
      }

      return patron2.test(tecla_valor) && control;
    } else {
      if (valInicial === obj.value) {
        obj.value = '';
      }
      return patron2.test(tecla_valor) && control;
    }
  }
</script>