<?php
$modelRuta = new ModelRuta();
$modelAutoconsumo = new ModelAutoconsumo();
$modelCompania = new ModelCompania();
$companias = $modelCompania->listaTodas();
if ($_SESSION["tipoUsuario"] == "u") $rutas = $modelAutoconsumo->obtenerEstacionesPorZona($_SESSION['zonaId'],5);

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Autoconsumo Estaciones</h1> / <a href="../view/index.php?action=autoconsumos/nuevo.php">Nuevo Autoconsumo</a>
</div>



<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
<!-- Formulario -->
<form action="../controller/Autoconsumos/InsertarAutoconsumoEstaciones.php" method="POST" enctype="multipart/form-data">
  <div class="row">
    <!-- Comprobante -->
    <div class="col">
      <div class="form-group">
        <label for="comprobante">Subir Comprobante de Autoconsumo:</label>
        <input type="file" name="comprobante" id="comprobante" accept="image/*" required>
      </div>
    </div>

    <!-- Estaciones -->
    <div class="col">
      <div class="form-group">
        <label>Estaciones:</label>
        <select class="form-control form-control-sm" id="ruta" name="ruta" required>
          <option selected disabled>Seleccione una</option>
          <?php
          foreach ($rutas as $data) {
            echo "<option value='" . $data["idruta"] . "'>" . $data["clave_ruta"] . "</option>";
          }
          ?>
        </select>
      </div>
    </div>

    <!-- Fecha -->
    <div class="col">
      <div class="form-group">
        <label>Fecha de Autoconsumo:</label>
        <input class="form-control form-control-sm" type="date" name="fecha" required>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Litros -->
    <div class="col">
      <div class="form-group">
        <label>Litros</label>
        <input class="form-control form-control-sm" type="text" name="litros" id="litros" onChange="calcular();" onkeydown="return decimales(this, event)" required>
      </div>
    </div>

    <!-- Costo por litro -->
    <div class="col">
      <div class="form-group">
        <label>Costo/litro</label>
        <input class="form-control form-control-sm" type="text" name="costo_litro" id="costo_litro" onChange="calcular();" onkeydown="return decimales(this, event)" required>
      </div>
    </div>
  </div>

  <!-- Total -->
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label>Costo Total</label>
        <input class="form-control form-control-sm" type="text" id="costo_total" disabled>
      </div>
    </div>
  </div>

  <!-- Botón -->
  <div class="row">
    <div class="col offset-md-11">
      <div class="form-group">
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
  function calcular() {
    const litros = parseFloat(document.getElementById("litros").value) || 0;
    const costoLitro = parseFloat(document.getElementById("costo_litro").value) || 0;
    const total = litros * costoLitro;
    document.getElementById("costo_total").value = total.toFixed(2);
  }
</script>
