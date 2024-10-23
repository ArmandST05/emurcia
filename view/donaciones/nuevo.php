<?php
$modelZona = new ModelZona();
$zonas = [$modelZona->obtenerZonaId($_SESSION['zonaId'])];
$fecha = date("Y-m-d");

?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=donaciones/index.php">Donaciones</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva Donación</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Donaciones/InsertarDonacion.php" method="post" enctype="multipart/form-data">

          <div class="row">
           <div class="col-md-4">  
                <label for="comprobante">Subir Comprobante de la Donación:</label>
                <input type="file" name="comprobante" id="comprobante" accept="image/*" required>  
            </div>
          </div>

          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Fecha</label>
                <input class="form-control form-control-sm" type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Zona</label>
                <select class="form-control form-control-sm" name="zona">
                  <?php foreach ($zonas as $zona) : ?>
                    <option value='<?php echo $zona["idzona"] ?>'><?php echo $zona["nombre"] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="form-group">
                <label>Kilogramos</label>
                <input class="form-control form-control-sm" type="text" name="cantidad" id="cantidad" onkeydown="return decimales(this, event)" onkeypress='validate(event)' required>
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label>Comentario</label>
                <input class="form-control form-control-sm" type="text" name="comentario" id="comentario" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-2 offset-11">
              <div class="form-group">
                <input class="btn btn-primary btn-sm" type="submit" value="Guardar">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script type="text/JavaScript">
  $(document).ready(function(){
  });

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    var regex2 =   /[ -~]/;
    if( regex2.test(key) && !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault){
                theEvent.preventDefault();
            }
    }  
  }
</script>