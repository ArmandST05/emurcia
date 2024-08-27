<?php
$modelCompras = new ModelCompraGasolina();
$modelZona = new ModelZona();
$modelProducto = new ModelProducto();

if ($_SESSION["tipoUsuario"] == "su") {
  $zonas = $modelZona->obtenerZonasGasolina();
  $zonaId = (!empty($_GET["zonaId"])) ? $_GET["zonaId"] : $zonas[0]["idzona"];
} else {
  $zonaId = $_SESSION['zonaId'];
}

$productos = $modelProducto->productosPorTipoZona(2); //Gasolina
$aceites = $modelCompras->obtenerAceitesZonaId($zonaId);
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="index.php?action=comprasgasolina/index.php">Compras Gasolina</a> /
    <a href="#">Nueva</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nueva compra Gasolina
  <?php if ($_SESSION["tipoUsuario"] == "su") : ?>
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
  <?php if ($zonaId) : ?>
    <div class="col-xl-12 col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          <form action="../controller/Compras/InsertarCompraGasolina.php" method="POST" name="form1">
            <div class="row">
              <table border="0">
                <tr>
                  <td>Fecha: </td>
                  <td>
                    <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" value="<?php echo date("Y-m-d") ?>">
                  </td>
                <tr>
                  <td>Producto: </td>
                  <td>
                    <select class="form-control form-control-sm" id="producto" name="producto" required>
                    <option value="" selected disabled>Selecciona uno</option>
                      <?php foreach ($productos as $producto) : ?>
                        <option value="<?php echo $producto['idproducto'] ?>">
                          <?php echo strtoupper($producto["nombre"]) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Aceite: </td>
                  <td><select class="form-control form-control-sm" id="aceiteId" name="aceite">
                      <option value="0" selected>Selecciona uno</option>
                      <?php foreach ($aceites as $aceite) : ?>
                        <option value="<?php echo $aceite['idaceite'] ?>"><?php echo $aceite["nombre"] ?></option>
                      <?php endforeach; ?>
                  </td>
                <tr>
                  <td>No. Factura: </td>
                  <td><input class="form-control form-control-sm" type="text" name="numFactura" id="numFactura" required></td>
                </tr>
                <tr>
                  <td>Fecha Descarga: </td>
                  <td>
                    <input type="date" class="form-control form-control-sm" name="fechaDescarga" id="fechaDescarga" value="<?php echo date("Y-m-d") ?>">
                  </td>
                <tr>
                <tr>
                  <td>Chofer: </td>
                  <td><input class="form-control form-control-sm" type="text" name="chofer" id="chofer" required></td>
                </tr>
                <tr>
                  <td>Litros: </td>
                  <td><input class="form-control form-control-sm" type="text" name="litros" onChange="calcularImporte();" id="litros" onkeypress="return SoloNumerosDecimales3(event, '0.0', 5);"></td>
                </tr>
                <tr>
                  <td>Precio: </td>
                  <td><input class="form-control form-control-sm" type="text" name="precio" onChange="calcularImporte();" id="precio" onkeypress="return onKeyDecimal(event,this);"></td>
                </tr>
                <tr>
                  <td>Importe: </td>
                  <td><input class="form-control form-control-sm" type="text" name="importe" id="importe" value="0" readonly onkeydown="return decimales(this, event)"></td>
                </tr>
                <tr>
                  <td>Tarifa: </td>
                  <td><input class="form-control form-control-sm" type="text" name="tarifa" onkeypress="return NumCheck(event, this);"></td>
                </tr>
              </table>
            </div>
            <div class="row">
              <div class="col-md-1 offset-md-11">
                <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                <input type="hidden" name="zona" value="<?php echo $zonaId ?>" required>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script type="text/JavaScript">
  $(document).ready(function() {
    $('#aceiteId').select2({});
  });

  function calcularImporte(){
    var precio = $('#precio').val();
    var litros = $('#litros').val();
    var importe = parseFloat(eval(precio*litros)).toFixed(5);
    $('#importe').val(parseFloat(eval(parseFloat(importe))).toFixed(5));
  }

  function SoloNumerosDecimales3(e, valInicial, nEntero, nDecimal) {
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

  function onKeyDecimal(e,thix) {
    var keynum = window.event ? window.event.keyCode : e.which;
    if (document.getElementById(thix.id).value.indexOf('.') != -1 && keynum == 46)
        return false;
    if ((keynum == 8 || keynum == 48 || keynum == 46))
        return true;
    if (keynum <= 47 || keynum >= 58) return false;
    return /\d/.test(String.fromCharCode(keynum));
  }

  function NumCheck(e, field) {
    key = e.keyCode ? e.keyCode : e.which
    if (key == 8) return true
    if (key > 47 && key < 58) {
      if (field.value == "") return true
      regexp = /.[0-9]{5}$/
      return !(regexp.test(field.value))
    }
    if (key == 46) {
      if (field.value == "") return false
      regexp = /^[0-9]+$/
      return regexp.test(field.value)
    }
    return false
  }

  function seleccionZona(){
      window.location.href = "index.php?action=comprasgasolina/nuevo.php&zonaId="+ $("#zonaId").val();
    }
</script>