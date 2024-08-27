<?php
$modelOrigenGasto = new ModelOrigenGasto();
$modelGasto = new ModelGasto();
$modelConceptoGasto = new ModelConceptoGasto();

$id = $_GET['id'];
$gasto = $modelGasto->obtenerGastoAdministrativoId($id);
if (empty($gasto)) {
  echo "<script> 
    alert('No se encontró el gasto');
    window.location.href = 'index.php?action=gastosadministrativos/index.php';
    </script>";
} else {
  $gasto = reset($gasto);
}

$origenes = $modelOrigenGasto->listaPorEstatusGastoEditar($gasto["origen_gasto_id"], 1);
//Tipo Gasto 1 (Administrativo) 2(PuntoVta)
//Estatus 0 (Inactivo) 1(Activo)
$conceptos = $modelConceptoGasto->listaPorTipoGastoEstatusGastoEditar($gasto["concepto_gasto_id"], 1, 1);

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

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=gastosadministrativos/index.php">Gastos Administrativos</a> /
    <a href="#">Editar</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Editar Gasto Administrativo</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <div class="card-body">
        <form action="../controller/Gastos/ActualizarGastoAdministrativo.php" method="POST">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Mes</label>
                <select class="form-control form-control-sm" name="mes" id="mes" required>
                  <?php foreach ($meses as $mes) : ?>
                    <option value="<?php echo $mes['id'] ?>" <?php echo ($gasto['mes'] == $mes['id']) ? "selected" : ""; ?>><?php echo $mes['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Año</label>
                <input class="form-control form-control-sm" type="text" name="anio" id="anio" value="<?php echo $gasto['anio'] ?>" readonly required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Origen</label>
                <select class="form-control form-control-sm" name="origen" id="origen" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($origenes as $origen) : ?>
                    <option value="<?php echo $origen['idorigengasto'] ?>" <?php echo ($gasto['origen_gasto_id'] == $origen['idorigengasto']) ? "selected" : ""; ?>><?php echo $origen['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label>Concepto</label>
                <select class="form-control form-control-sm" name="concepto" id="concepto" required>
                  <option value="" selected disabled>Seleccione Opción</option>
                  <?php foreach ($conceptos as $concepto) : ?>
                    <option value="<?php echo $concepto['idconceptogasto'] ?>" <?php echo ($gasto['concepto_gasto_id'] == $concepto['idconceptogasto']) ? "selected" : ""; ?>><?php echo $concepto['nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Cantidad</label>
                <input class="form-control form-control-sm" type="number" min="0" step="0.1" name="cantidad" id="cantidad" value="<?php echo $gasto['cantidad'] ?>" required>
              </div>
              <div class="form-group">
                <label>Observaciones</label>
                <textarea class="form-control form-control-sm" name="observaciones" id="observaciones"><?php echo $gasto['observaciones'] ?></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-1 offset-md-11">
              <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
            </div>
          </div>
          <input class="form-control form-control-sm" type="hidden" name="id" id="id" value="<?php echo $id ?>">
          <input class="form-control form-control-sm" type="hidden" name="zona" id="zona" value="<?php echo $gasto['zona_id'] ?>">
          <input class="form-control form-control-sm" type="hidden" name="zonaId" id="zonaId" value="<?php echo $gasto['zona_id'] ?>">
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">

</script>