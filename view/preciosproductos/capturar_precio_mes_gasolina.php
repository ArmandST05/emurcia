<?php
$modelProducto = new ModelProducto();
$modelPrecioProducto = new ModelPrecioProducto();
$modelZona = new ModelZona();
$productos = $modelProducto->productosPorTipoZona(2); //Gasolina

if ($_SESSION["tipoUsuario"] == "su") {
  $zonas = $modelZona->obtenerZonasGasolina();
  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : (($zonas) ? $zonas[0]["idzona"]:"");
} else {
  $zonaId = $_SESSION['zonaId'];
}

$precioMagna = 0;
$precioPremium = 0;
$precioDiesel = 0;

$precioMagna = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaId, 6);
$precioMagna = reset($precioMagna);
$precioPremium = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaId, 7);
$precioPremium = reset($precioPremium);
$precioDiesel = $modelPrecioProducto->obtenerPrecioGasolinaZonaProductoId($zonaId, 8);
$precioDiesel = reset($precioDiesel);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="#">Precio Mes Gasolina</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Precio Mes Gasolina</h1>
</div>
<!-- Content Row -->
<div class="row">
  <div class="col-xl-8 col-lg-8">
    <div class="card shadow mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Capturar precio del mes
          <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
            <div class="form-group">
              <select class="form-control form-control-sm" id="zonaId" onchange="seleccionZona()">
                <?php foreach ($zonas as $dataZona) : ?>
                  <option value="<?php echo $dataZona['idzona'] ?>" <?php echo (($zonaId == $dataZona['idzona']) ? "selected":"") ?>>
                    <?php echo strtoupper($dataZona["nombre"]) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>
        </h6>
      </div>
      <!-- Card Body -->
      <?php if ($zonaId) : ?>
        <div class="card-body">
          <form action="../controller/PreciosProducto/PrecioMesGasolina.php" method="POST">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-2">
                    <label>Fecha: </label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="date" name="fecha" value="<?php echo date("Y-m-d") ?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label> Precio del mes: </label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="price" onkeypress='validate(event)' required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>Producto: </label>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control form-control-sm" id="producto" name="producto" required>
                      <?php foreach ($productos as $producto) : ?>
                        <option value="<?php echo $producto['idproducto'] ?>">
                          <?php echo strtoupper($producto["nombre"]) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <label>IEPS</label>
                  </div>
                  <div class="col-md-6">
                    <input class="form-control form-control-sm" type="text" name="ieps" onkeypress='validate(event)' required>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2 offset-11">
                <div class="form-group">
                  <input class="btn btn-primary btn-sm" type="submit" value="Guardar">
                  <input class="form-control form-control-sm" type="hidden" name="zona" value="<?php echo $zonaId ?>" required>
                </div>
              </div>
            </div>
          </form>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($zonaId) : ?>
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Precios de <?php echo date("d-m-Y") ?></h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <label>Magna</label>
            </div>
            <div class="col">
              <label>$<?php echo $precioMagna['precio'] ?></label>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>Premium</label>
            </div>
            <div class="col">
              <label>$<?php echo $precioPremium['precio'] ?></label>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <label>Diesel</label>
            </div>
            <div class="col">
              <label>$<?php echo $precioDiesel['precio'] ?></label>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
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
  function soloNumerosDecimales3(e, valInicial, nEntero, nDecimal) {
    var obj = e.srcElement || e.target;
    var tecla_codigo = (document.all) ? e.keyCode : e.which;
    var tecla_valor = String.fromCharCode(tecla_codigo);
    var patron2 = /[\d.]/;
    var control = (tecla_codigo === 46 && (/[.]/).test(obj.value)) ? false : true;
    var existePto = (/[.]/).test(obj.value);

    //el tab
    if (tecla_codigo === 8)
        return true;

    if (valInicial !== obj.value) {
        var TControl = obj.value.length;
        if (existePto === false && tecla_codigo !== 46) {
            if (TControl === nEntero) {
                obj.value = obj.value + ".";
            }
        }

        if (existePto === true) {
            var subVal = obj.value.substring(obj.value.indexOf(".") + 1, obj.value.length);

            if (subVal.length > 1) {
                return false;
            }
        }

        return patron2.test(tecla_valor) && control;
    }
    else {
        if (valInicial === obj.value) {
            obj.value = '';
        }
        return patron2.test(tecla_valor) && control;
    }
  }

  function seleccionZona(){
      window.location.href = "index.php?action=preciosproductos/capturar_precio_mes_gasolina.php&zonaId="+ $("#zonaId").val();
    }
</script>