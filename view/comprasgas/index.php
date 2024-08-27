<?php
$modelCompras = new ModelCompra();
$modelZona = new ModelZona();
if ($_SESSION['tipoUsuario'] == "u" && $_SESSION["tipoZona"] != 1) {
  echo "<script> 
      alert('Tu zona no vende GAS... Redireccionando compras de gasolina');
      window.location.href = 'index.php?action=comprasgasolina/index_gasolina.php';
    </script>";
}

$zonas = $modelZona->obtenerZonasGas();

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "uc" || $_SESSION["tipoUsuario"] == "inv") {
  $zona = (isset($_GET["zona"])) ? $_GET["zona"] : "";
} else $zona = $_SESSION["zonaId"];

$mes = (!empty($_GET["mes"])) ? $_GET["mes"] : $anio = date("m");
$anio = (!empty($_GET["anio"])) ? $_GET["anio"] : $anio = date("Y");

$fecha = $anio . "-" . $mes;
$arrayFecha = explode("-", $fecha, 3);

$month = $anio . "-" . $mes;
$aux = date('Y-m-d', strtotime("{$month} + 1 month"));
$last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));

$fechai = $anio . "-" . $mes . "-01";
$fechaf = $last_day;

$compras = $modelCompras->obtenerZonaFechas($zona, $fechai, $fechaf);

$totalCompras = 0;
$kilogramosTotal = 0;
$litrosTotal = 0;
$totalDensidad = 0;
$promedioDensidad = 0;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Compras Gas</a>
  </div>
</div>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Compras Gas</h1>
  <?php if ($_SESSION["tipoUsuario"] != "inv") : ?>
    <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" href="index.php?action=comprasgas/nuevo.php">Nuevo</a>
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
              <div class="form-group">
                <label>Zona:</label>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <select class="form-control form-control-sm" name="zona">
                  <?php foreach ($zonas as $dataZona) : ?>
                    <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zona == $dataZona['idzona']) ? "selected" : "" ?>>
                      <?php echo $dataZona["nombre"] ?>
                    </option>
                  <?php endforeach; ?>

                </select>
              </div>
            </div>
          </div>
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
                    if ($arrayFecha[1] == $j) {
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
                    if ($arrayFecha[0] == $k) {
                      echo " selected='selected'";
                    }
                    echo ">" . $k . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <input type='hidden' name='action' id='action' value="comprasgas/index.php" />
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
<div class="row">
  <!-- Card -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Header -->
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Lista de compras</h6>
      </div>
      <!-- Card Body -->
      <div class="card-body">
        <button class="btn btn-sm btn-warning" id="btnExport">Exportar a Excel</button>
        <table class="table table-bordered table-sm table-responsive" id="listaTabla" name="listaTabla">
          <thead>
            <tr>
              <th> Fecha Embarque </th>
              <th> No. Factura </th>
              <th> Transporte </th>
              <th> Fecha Descarga </th>
              <th> Origen</th>
              <th> Destino </th>
              <th> Kilogramos </th>
              <th> Densidad</th>
              <th> Litros</th>
              <th> Empresa </th>
              <th> Fecha Pago </th>
              <th> Zona Descarga </th>
              <th> Acciones </th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($compras as $compra) : ?>
              <tr align="center">
                <td><?php echo $compra["fechacompra"] ?></td>
                <td><?php echo $compra["nocompra"] ?></td>
                <td><?php echo $compra["proveedor"] ?></td>
                <td><?php echo $compra["fechaembarque"] ?></td>
                <td><?php echo $compra["origen"] ?></td>
                <td><?php echo $compra["destino"] ?></td>
                <td><?php echo number_format($compra["kilogramos"], 0) ?></td>
                <td><?php echo $compra["densidad"] ?></td>
                <td><?php echo number_format($compra["litros"], 2) ?></td>
                <td><?php echo $compra["zona_nombre"] ?></td>
                <td><?php echo $compra["fechapago"] ?></td>
                <td><?php echo $compra["descargada"] ?></td>
                <td>
                  <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "u") : ?>
                    <button class='btn btn-sm btn-primary' type='button' onclick="eliminar('<?php echo $compra['idcompragas']; ?>');"><i class='fas fa-trash'></i></button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php
              $litros = $compra["litros"];
              $litrosTotal += $litros;
              $litros = $compra["litros"];

              $totalDensidad += $compra["densidad"];
              $totalCompras++;

              $kilogramos = $compra["kilogramos"];
              $kilogramosTotal += $kilogramos;
            endforeach;
            if ($totalCompras > 0) $promedioDensidad = $totalDensidad / $totalCompras;
            ?>
            <tr class="bg-light" align="center">
              <th>Total</th>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>
                <strong><?php echo number_format(($kilogramosTotal), 2) ?></strong>
              </td>
              <td>
                <strong><?php echo number_format(($promedioDensidad), 2) ?></strong>
              </td>
              <td>
                <strong><?php echo number_format(($litrosTotal), 2) ?></strong>
              </td>
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

<script type="text/javascript">
  $(document).ready(function() {

  });

  /*$('#listaTabla').DataTable({
    "pageLength": 25,
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
    }
  });*/

  $("#btnExport").click(function(e) {
    $("#listaTabla").btechco_excelexport({
      containerid: "listaTabla",
      datatype: $datatype.Table,
      filename: 'Compras Gas'
    });
  });

  function eliminar(id) {
    alertify.confirm("¿Realmente desea eliminar la compra?",
        function() {
          $.ajax({
            type: "POST",
            url: "../controller/Compras/EliminarCompraGas.php",
            data: {
              id: id
            },
            success: function(data) {
              location.reload();
              alertify.success("Compra eliminada");
            }
          });
        },
        function() {})
      .set({
        title: "Eliminar compra"
      })
      .set({
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      });
  }
</script>