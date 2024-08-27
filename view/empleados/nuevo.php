<?php
include_once('../model/ModelZona.php');
include_once('../model/ModelEmpleado.php');

$modelEmpleado = new ModelEmpleado();
$modelZona = new ModelZona();
$zonas = $modelZona->listaTodas();
$tiposEmpleados = $modelEmpleado->obtenerTiposEmpleados();
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=empleados/index.php">Empleados</a> /
    <a href="#">Nuevo Empleado</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Empleado</h1>
</div>

<div class="row">

  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">

      <div class="card-body">
        <form action="../controller/Empleados/InsertarEmpleado.php" method="post" id="agregarEmpleadoForm">
          <div class="row">
            <div class="col-md-2">
              <label>Zona:</label>
            </div>
            <div class="col-md-6">
              <select class="form-control form-control-sm" name="zona_id" id="zona">
                <option value="" selected disable hidden readonly value="0">Seleccionar zona</option>
                <?php foreach ($zonas as $zonaData) : ?>
                  <option value="<?php echo $zonaData['idzona'] ?>">
                    <?php echo strtoupper($zonaData['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label>Nombre</label>
            </div>
            <div class="col-md-6">
              <input class="form-control form-control-sm" type="text" name="nombre" id="nombre" value="" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label>Puesto</label>
            </div>
            <div class="col-md-6">
              <select class="form-control form-control-sm" require name="puesto">
                <?php foreach ($tiposEmpleados as $tipoEmpleado) : ?>
                  <option value="<?php echo $tipoEmpleado['idtipoempleado'] ?>"><?php echo $tipoEmpleado['nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label>Sueldo base</label>
            </div>
            <div class="col-md-6">
              <input class="form-control form-control-sm" type="number" name="sueldo_base" id="sueldo_base" value="0" step="0.01" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <label>Infonavit</label>
            </div>
            <div class="col-md-6">
              <input class="form-control form-control-sm" type="number" name="infonavit" id="infonavit" value="0" step="0.01" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#zona').select2({});
  });
</script>