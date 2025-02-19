<<<<<<< HEAD
<?php
$modelRuta = new ModelRuta();
$modelConceptoGasto = new ModelConceptoGasto();
$modelZona = new ModelZona();

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "ga") {
  $zonas = $modelZona->obtenerZonasTodas();

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
  $zona = $_SESSION['zona'];
}

$rutas = $modelRuta->listaPorZonaEstatus($zonaId, 1);
$conceptos = $modelConceptoGasto->listaPorTipoGastoEstatus(2, 1);

$meses = [
  ["id" => "01", "nombre" => "Enero"],
  ["id" => "02", "nombre" => "Febrero"],
  ["id" => "03", "nombre" => "Marzo"],
  ["id" => "04", "nombre" => "Abril"],
  ["id" => "05", "nombre" => "Mayo"],
  ["id" => "06", "nombre" => "Junio"],
  ["id" => "07", "nombre" => "Julio"],
  ["id" => "08", "nombre" => "Agosto"],
  ["id" => "09", "nombre" => "Septiembre"],
  ["id" => "10", "nombre" => "Octubre"],
  ["id" => "11", "nombre" => "Noviembre"],
  ["id" => "12", "nombre" => "Diciembre"],
];

$mesActual = date("m");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Gasto Punto Venta
    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "ga") : ?>
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
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/Gastos/InsertarGastoRuta.php" method="POST">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
              <div class="col-md-4">
                  <input type="file" name="comprobante" id="comprobante" accept="image/*" required>
                 </div>
              </div>
              <div class="form-group">
                <label>Mes</label>
                <select class="form-control form-control-sm" name="mes" id="mes" required>
                  <?php foreach ($meses as $mes) : ?>
                    <option value="<?php echo $mes['id'] ?>" <?php echo ($mes["id"] == $mesActual) ? "selected" : "" ?>><?php echo $mes['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Año</label>
                <input class="form-control form-control-sm" type="text" name="anio" id="anio" value="<?php echo date("Y") ?>" readonly required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Ruta</label>
                <select class="form-control form-control-sm" name="rutaId" id="rutaId" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($rutas as $ruta) : ?>
                    <option value="<?php echo $ruta['idruta'] ?>"><?php echo $ruta['clave_ruta'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Concepto</label>
                <select class="form-control form-control-sm" name="concepto" id="concepto" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($conceptos as $concepto) : ?>
                    <option value="<?php echo $concepto['idconceptogasto'] ?>"><?php echo $concepto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cantidad</label>
                <input class="form-control form-control-sm" type="number" min="0" step=".01" name="cantidad" id="cantidad" required>
              </div>
              <div class="form-group">
                <label>Observaciones</label>
                <textarea class="form-control form-control-sm" name="observaciones" id="observaciones"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
          <input class="form-control form-control-sm" type="hidden" name="zona" id="zona" value="<?php echo $zonaId ?>">
          <input class="form-control form-control-sm" type="hidden" name="zonaId" id="zonaId" value="<?php echo $zonaId ?>">
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  function seleccionZona(){
      window.location.href = "index.php?action=gastosruta/nuevo.php&zonaId="+ $("#zonaId").val();
    }
=======
<?php
$modelRuta = new ModelRuta();
$modelConceptoGasto = new ModelConceptoGasto();
$modelZona = new ModelZona();

if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "ga") {
  $zonas = $modelZona->obtenerZonasTodas();

  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
  $zona = $_SESSION['zona'];
}

$rutas = $modelRuta->listaPorZonaEstatus($zonaId, 1);
$conceptos = $modelConceptoGasto->listaPorTipoGastoEstatus(2, 1);

$meses = [
  ["id" => "01", "nombre" => "Enero"],
  ["id" => "02", "nombre" => "Febrero"],
  ["id" => "03", "nombre" => "Marzo"],
  ["id" => "04", "nombre" => "Abril"],
  ["id" => "05", "nombre" => "Mayo"],
  ["id" => "06", "nombre" => "Junio"],
  ["id" => "07", "nombre" => "Julio"],
  ["id" => "08", "nombre" => "Agosto"],
  ["id" => "09", "nombre" => "Septiembre"],
  ["id" => "10", "nombre" => "Octubre"],
  ["id" => "11", "nombre" => "Noviembre"],
  ["id" => "12", "nombre" => "Diciembre"],
];

$mesActual = date("m");
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=gastosruta/index.php">Gastos Punto Venta</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Gasto Punto Venta
    <?php if ($_SESSION["tipoUsuario"] == "su" || $_SESSION["tipoUsuario"] == "ga") : ?>
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
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/Gastos/InsertarGastoRuta.php" method="POST" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-4">
            <div class="form-group">
              <input type="file" name="comprobante" id="comprobante" accept="image/*" required>
            </div>
              <div class="form-group">
                <label>Mes</label>
                <select class="form-control form-control-sm" name="mes" id="mes" required>
                  <?php foreach ($meses as $mes) : ?>
                    <option value="<?php echo $mes['id'] ?>" <?php echo ($mes["id"] == $mesActual) ? "selected" : "" ?>><?php echo $mes['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Año</label>
                <input class="form-control form-control-sm" type="text" name="anio" id="anio" value="<?php echo date("Y") ?>" readonly required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Ruta</label>
                <select class="form-control form-control-sm" name="rutaId" id="rutaId" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($rutas as $ruta) : ?>
                    <option value="<?php echo $ruta['idruta'] ?>"><?php echo $ruta['clave_ruta'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Concepto</label>
                <select class="form-control form-control-sm" name="concepto" id="concepto" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($conceptos as $concepto) : ?>
                    <option value="<?php echo $concepto['idconceptogasto'] ?>"><?php echo $concepto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cantidad</label>
                <input class="form-control form-control-sm" type="number" min="0" step=".01" name="cantidad" id="cantidad" required>
              </div>
              <div class="form-group">
                <label>Observaciones</label>
                <textarea class="form-control form-control-sm" name="observaciones" id="observaciones"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
          <input class="form-control form-control-sm" type="hidden" name="zona" id="zona" value="<?php echo $zonaId ?>">
          <input class="form-control form-control-sm" type="hidden" name="zonaId" id="zonaId" value="<?php echo $zonaId ?>">
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  function seleccionZona(){
      window.location.href = "index.php?action=gastosruta/nuevo.php&zonaId="+ $("#zonaId").val();
    }
>>>>>>> 545fb7b87c3c1357fd81bd614d4e9552b29f5d45
</script>