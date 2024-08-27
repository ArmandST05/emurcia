<?php
$modelZona = new ModelZona();
$zonaUsuario = $_SESSION["zona"];
$zonas = ($_SESSION["tipoZona"] == 1) ? $modelZona->obtenerZonasGas() : $modelZona->obtenerZonasGasolina() ;
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=traspasos/index.php">Traspasos</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Traspaso</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Traspasos/InsertarTraspaso.php" method="POST">
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Zona Origen</label>
                <input class="form-control form-control-sm" id="zonaOrigen" name="zonaOrigen" type="text" value="<?php echo strtoupper($zonaUsuario) ?>" readonly>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Zona Destino</label>
                <select class="form-control form-control-sm" id="zonaDestino" name="zonaDestino">
                  <?php foreach ($zonas as $zona) : ?>
                    <option value="<?php echo $zona['idzona'] ?>">
                      <?php echo strtoupper($zona["nombre"]) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Cantidad</label>
                <input class="form-control form-control-sm" type="number" name="cantidad" id="cantidad" min="0" step=".01" required>
              </div>
            </div>
          </div>
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
  $(document).ready(function() {
  });
</script>