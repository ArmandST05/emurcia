<?php
$modelZona = new ModelZona();
$zonas = $modelZona->obtenerTodas();
$modelDescuento = new ModelDescuento();
$descuentos = $modelDescuento->index();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=clientesdescuento/index.php">Clientes Descuento</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Cliente Descuento</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/ClientesDescuento/Insertar.php" method="POST">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Nombre</label>
                <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Giro</label>
                <input type="text" class="form-control form-control-sm" name="giro" id="giro" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Calle</label>
                <input type="text" class="form-control form-control-sm" name="calle" id="calle" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Número</label>
                <input type="text" class="form-control form-control-sm" name="numero" id="numero" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Colonia</label>
                <input type="text" class="form-control form-control-sm" name="colonia" id="colonia" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Municipio</label>
                <input type="text" class="form-control form-control-sm" name="municipio" id="municipio" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Zona</label>
                <select class="form-control form-control-sm" name="zonaId" id="zonaId" required>
                  <option value="0" selected disabled>Seleccione Opción</option>
                  <?php foreach ($zonas as $zona) : ?>
                    <option value="<?php echo $zona['idzona'] ?>">
                      <?php echo $zona['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Descuento otorgado</label>
                <select class="form-control form-control-sm" name="descuentoId" id="descuentoId" required>
                  <option value="0" selected disabled>Seleccione Opción</option>
                  <?php foreach ($descuentos as $descuento) : ?>
                    <option value="<?php echo $descuento['iddescuento'] ?>">
                      <?php echo $descuento['cantidad'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
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
  $(document).ready(function() {});
</script>