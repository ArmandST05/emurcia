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
      <form action="../controller/Traspasos/InsertarTraspaso.php" method="POST" enctype="multipart/form-data">
  <div class="row mb-3">
    <div class="col-md-4">
      <label for="comprobante_traspaso">Subir Comprobante de Traspaso:</label>
      <input type="file" name="comprobante_traspaso" id="comprobante_traspaso" accept="image/*" required class="form-control form-control-sm">
    </div>
  </div>

  <!-- Primera fila: Zona Origen y Zona Destino -->
  <div class="row mb-3">
    <div class="col">
      <div class="form-group">
        <label for="zonaOrigen">Zona Origen</label>
        <input class="form-control form-control-sm" id="zonaOrigen" name="zonaOrigen" type="text" value="<?php echo strtoupper($zonaUsuario) ?>" readonly>
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <label for="zonaDestino">Zona Destino</label>
        <select class="form-control form-control-sm" id="zonaDestino" name="zonaDestino">
          <option value="">Selecciona una zona</option>
          <?php foreach ($zonas as $zona) : ?>
            <option value="<?php echo $zona['idzona'] ?>">
              <?php echo strtoupper($zona["nombre"]) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!-- Segunda fila: Estación Destino y Cantidad -->
  <div class="row mb-3">
    <div class="col">
      <div class="form-group">
        <label for="destinoEstacion">Estación Destino</label>
        <select class="form-control form-control-sm" id="estacionDestino" name="destinoEstacion">
          <option value="">Selecciona una estación</option>
        </select>
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <label for="cantidad">Cantidad</label>
        <input class="form-control form-control-sm" type="number" name="cantidad" id="cantidad" min="0" step=".01" required>
      </div>
    </div>
  </div>

  <!-- Botón Guardar -->
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
$(document).ready(function () {
    $('#zonaDestino').on('change', function () {
        var zonaId = $(this).val();
        console.log('📥 Zona seleccionada:', zonaId);

        $.ajax({
            url: '../controller/Traspasos/ObtenerEstaciones.php',
            method: 'POST',
            data: { zonaId: zonaId },
            dataType: 'json',

            success: function (response) {

if (response.success) {
    var $select = $('#estacionDestino');
    $select.empty(); // Limpiar opciones anteriores

    if (response.data.length === 0) {
        $select.append(
            $('<option>', {
                value: '',
                text: 'No hay estaciones'
            })
        );
    } else {
        response.data.forEach(function (estacion) {
            $select.append(
                $('<option>', {
                    value: estacion.idruta,
                    text: estacion.clave_ruta
                })
            );
        });
    }

} else {
    alert('⚠️ Error: ' + response.message);
}
}

        });
    });
});
</script>
