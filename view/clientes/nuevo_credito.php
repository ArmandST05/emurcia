<?php
$modelZona = new ModelZona();
$zonas = $modelZona->obtenerTodas();
?>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <div class="inline-block">
    <a href="#"><i class="fas fa-home fa-sm"></i></a> /
    <a href="../view/index.php?action=clientes/index_credito.php">Clientes Crédito</a> /
    <a href="#">Nuevo</a>
  </div>
</div>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Nuevo Cliente Crédito</h1>
</div>

<!-- Content Row -->
<div class="row">
  <!-- Nuevo Pedido -->
  <div class="col-xl-12 col-lg-12">
    <div class="card shadow mb-4">
      <!-- Card Body -->
      <div class="card-body">
        <form action="../controller/Clientes/InsertarCliente.php" method="post" id="uniuti">
          <div class="row">
            <table border="0">
              <tr>
                <td>VTA PÚBLICO EN GRAL</td>
                <td><input type="checkbox" onchange="seleccionarPublicoGeneral()" id="publico" name="publi"></td>
              </tr>
              <tr>
                <td>Nombre cliente</td>
                <td><input class="form-control form-control-sm" type="text" name="name" id="name" required></td>
              </tr>
              <tr>
                <td>Domicilio</td>
                <td><input class="form-control form-control-sm" type="text" name="dom" id="dom" required></td>
              </tr>
              <tr>
                <td>Colonia</td>
                <td><input class="form-control form-control-sm" type="text" name="col" id="col" required></td>
              </tr>
              <tr>
                <td>Nombre comercial</td>
                <td><input class="form-control form-control-sm" type="text" name="tipneg" id="tipneg" required></td>
              </tr>
              <tr>
                <td>Límite de crédito</td>
                <td>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input class="form-control form-control" type="text" name="credit" id="credit" onkeypress='validate(event)' required>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Precio con descuento</td>
                <td>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text">$</span>
                    </div>
                    <input class="form-control form-control-sm" type="text" name="pre" id="pre" onkeypress='validate(event)'>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Zona</td>
                <td><select class="form-control form-control-sm" name="zone">
                    <?php
                    foreach ($zonas as $data) : ?>
                      <option value="<?php echo $data['idzona'] ?>"><?php echo $data["nombre"] ?></option>
                    <?php endforeach; ?>
                  </select></td>
              </tr>
            </table>
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

  function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    var regex2 = /[ -~]/;
    if (regex2.test(key) && !regex.test(key)) {
      theEvent.returnValue = false;
      if (theEvent.preventDefault) {
        theEvent.preventDefault();
      }
    }
  }

  function seleccionarPublicoGeneral() {
    if (document.getElementById('publico').checked) {
      document.getElementById('name').value = "VTA PUBLICO EN GRAL";
      document.getElementById('name').readOnly = true;
    } else {
      document.getElementById('name').value = "";
      document.getElementById('name').readOnly = false;
    }
  }
</script>