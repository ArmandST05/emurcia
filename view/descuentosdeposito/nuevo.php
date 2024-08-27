<?php
$modelZona = new ModelZona();

if ($_SESSION["tipoUsuario"] == "mv") { //Es un usuario multizona de captura de ventas
  $zonas = $modelZona->obtenerZonasPorUsuario($_SESSION["id"]);

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
}

$fecha = date('Y-m-d');
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=descuentosdeposito/index.php">Detallado Depósitos</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo detalle depósito
    <?php if ($_SESSION["tipoUsuario"] == "mv") : ?>
      <div class="form-group">
        <select class="form-control form-control-sm" id="zonaId" onchange="seleccionZona()" required>
          <?php foreach ($zonas as $dataZona) : ?>
            <option value="<?php echo $dataZona['idzona'] ?>" <?php echo ($zonaId == $dataZona['idzona']) ? "selected" : "" ?>>
              <?php echo strtoupper($dataZona["nombre"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

  </h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/DescuentosDeposito/Insertar.php" method="POST" id="formNuevoDescuento">
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Fecha</label>
                <input class="form-control form-control-sm" type="date" name="fecha" id="fecha" value="<?php echo $fecha ?>">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Pago Electrónico (PE)</label>
                <input class="form-control form-control-sm" id="pagoElectronico" name="pagoElectronico" type="number" value="0" min="0" step=".01">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Cheque (CH)</label>
                <input class="form-control form-control-sm" id="cheque" name="cheque" type="number" value="0" min="0" step=".01">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Transferencias (T)</label>
                <input class="form-control form-control-sm" id="otrasSalidas" name="otrasSalidas" type="number" value="0" min="0" step=".01">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Gastos (GT)</label>
                <input class="form-control form-control-sm" id="gastos" name="gastos" type="number" value="0" min="0" step=".01">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label>Vale de Retiro (VR)</label>
                <input class="form-control form-control-sm" id="valeRetiro" name="valeRetiro" type="number" value="0" min="0" step=".01">
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Descripción de Vale de Retiro (VR)</label>
                <input class="form-control form-control-sm" id="descripcionValeRetiro" name="descripcionValeRetiro" type="text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col offset-md-11">
              <div class="form-group">
                <input type="hidden" value="<?php echo $zonaId ?>" name="zonaId">
                <input class="btn btn-sm btn-primary" type="submit" value="Guardar">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {});

  //Validar formulario
  $("#formNuevoDescuento").submit(function(event) {
    let pagoElectronico = parseFloat($("#pagoElectronico").val());
    let valeRetiro = parseFloat($("#valeRetiro").val());
    let descripcionValeRetiro = $("#descripcionValeRetiro").val();
    let gastos = parseFloat($("#gastos").val());
    let cheque = parseFloat($("#cheque").val());
    let otrasSalidas = parseFloat($("#otrasSalidas").val());

    if (pagoElectronico > 0 || valeRetiro > 0 || gastos > 0 || cheque > 0 || otrasSalidas > 0) {
      if (valeRetiro > 0 && !descripcionValeRetiro.trim()) {
        event.preventDefault();
        alertify.error("Ingresa la descripción del Vale de Retiro");
      }
      return true;
    } else {
      event.preventDefault();
      alertify.error("Ingresa los datos del detalle");
    }
  });

  function seleccionZona() {
    window.location.href = "index.php?action=descuentosdeposito/nuevo.php&zonaId=" + $("#zonaId").val();
  }
</script>